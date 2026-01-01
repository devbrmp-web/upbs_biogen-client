<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function catalogindex()
    {
        $url = config('app.url_dev_admin');

        $search = request()->query('search');
        $forceRefresh = (bool) request()->boolean('refresh');

        // Support ID & slug
        $commodityId        = request()->query('commodity_id');
        $commoditySlugParam = request()->query('commodity');

        // Support ID & code
        $seedClassIdParam   = request()->query('seed_class_id');
        $seedClassCodeParam = request()->query('seed_class');

        try {

            /* =====================================================
            | MASTER DATA (CACHED)
            ===================================================== */
            if ($forceRefresh) {
                $seedClasses = Http::timeout(5)->get($url . '/api/seed-classes')->json('data') ?? [];
                Cache::put('seed_classes_all', $seedClasses, 3600);
            } else {
                $seedClasses = Cache::remember('seed_classes_all', 3600, function () use ($url) {
                    return Http::timeout(5)
                        ->get($url . '/api/seed-classes')
                        ->json('data') ?? [];
                });
            }

            if ($forceRefresh) {
                $commodities = Http::timeout(5)->get($url . '/api/commodities')->json('data') ?? [];
                Cache::put('commodities_all', $commodities, 3600);
            } else {
                $commodities = Cache::remember('commodities_all', 3600, function () use ($url) {
                    return Http::timeout(5)
                        ->get($url . '/api/commodities')
                        ->json('data') ?? [];
                });
            }

            /* =====================================================
            | RESOLVE ACTIVE SEED CLASS
            ===================================================== */
            $activeSeedClassId   = null;
            $activeSeedClassCode = null;

            if (!empty($seedClassIdParam) && is_numeric($seedClassIdParam)) {
                $activeSeedClassId = (int) $seedClassIdParam;
                $match = collect($seedClasses)->firstWhere('id', $activeSeedClassId);
                $activeSeedClassCode = $match['code'] ?? null;
            } elseif (!empty($seedClassCodeParam)) {
                $match = collect($seedClasses)->firstWhere('code', $seedClassCodeParam);
                if ($match) {
                    $activeSeedClassId   = $match['id'];
                    $activeSeedClassCode = $match['code'];
                }
            }

            /* =====================================================
            | RESOLVE ACTIVE COMMODITY
            ===================================================== */
            $activeCommoditySlug = null;

            if (!empty($commodityId) && is_numeric($commodityId)) {
                $c = collect($commodities)->firstWhere('id', (int) $commodityId);
                $activeCommoditySlug = strtolower($c['slug'] ?? '');
            } elseif (!empty($commoditySlugParam)) {
                $activeCommoditySlug = strtolower($commoditySlugParam);
            }

            /* =====================================================
            | FETCH VARIETIES
            ===================================================== */
            $varieties = [];

            if ($activeSeedClassId) {
                // Scenario A: Filter by Seed Class (Use specific endpoint)
                $cacheKey = "varieties_by_class_{$activeSeedClassId}";
                if ($forceRefresh) {
                    $response = Http::timeout(5)->get($url . "/api/seed-classes/{$activeSeedClassId}/varieties");
                    $varieties = $response->successful() ? ($response->json('data') ?? []) : [];
                    Cache::put($cacheKey, $varieties, 300);
                } else {
                    $varieties = Cache::remember($cacheKey, 300, function () use ($url, $activeSeedClassId) {
                        $response = Http::timeout(5)->get($url . "/api/seed-classes/{$activeSeedClassId}/varieties");
                        if ($response->successful()) {
                            return $response->json('data') ?? [];
                        }
                        return [];
                    });
                }

                // Normalize stock data for the view
                // The endpoint returns 'stock_by_class' => ['class_id' => ..., 'total' => ...]
                // We need to inject this so the view can display it
                $seedClassName = collect($seedClasses)->firstWhere('id', $activeSeedClassId)['name'] ?? $activeSeedClassCode;
                
                $varieties = array_map(function ($v) use ($activeSeedClassCode, $seedClassName) {
                    // If the API returns stock_by_class, use it
                    if (isset($v['stock_by_class']['total'])) {
                        $v['stock_by_class'] = [
                            $activeSeedClassCode => [
                                'name' => $seedClassName,
                                'stock' => $v['stock_by_class']['total']
                            ]
                        ];
                    }
                    return $v;
                }, $varieties);

            } else {
                // Scenario B: No Seed Class Filter (Fetch All)
                if ($forceRefresh) {
                    $response = Http::timeout(5)->get($url . '/api/varieties');
                    if ($response->successful()) {
                        $data = $response->json('data') ?? [];
                        $varieties = array_map(function($item) {
                            if (!isset($item['images'])) $item['images'] = [];
                            return $item;
                        }, $data);
                        Cache::put('varieties_all', $varieties, 1800);
                    } else {
                        $varieties = [];
                    }
                } else {
                    $varieties = Cache::remember('varieties_all', 1800, function () use ($url) {
                        $response = Http::timeout(5)->get($url . '/api/varieties');
                        if ($response->successful()) {
                            // Tambahkan fallback images array jika kosong agar tidak error di view
                            $data = $response->json('data') ?? [];
                            return array_map(function($item) {
                                if (!isset($item['images'])) $item['images'] = [];
                                return $item;
                            }, $data);
                        }
                        return [];
                    });
                }
                
                // Note: /api/varieties does NOT return seed_lots or stock breakdown.
                // We rely on 'stock' => ['total_stock_kg' => ...]
            }

            /* =====================================================
            | CALCULATE PRICE RANGES (Client Side) with Caching
            ===================================================== */
            $varieties = array_map(function ($v) use ($url) {
                $slug = $v['slug'] ?? '';
                if (empty($slug)) {
                    $v['price_range_text'] = null;
                    return $v;
                }
                
                // Cache key untuk price range
                $cacheKey = "variety_price_{$slug}";
                
                // Gunakan cache dengan TTL 60 menit (3600 detik) untuk optimal performance
                $priceRangeText = Cache::remember($cacheKey, 3600, function () use ($url, $slug) {
                    try {
                        // Coba ambil detail varietas untuk mendapatkan seed_lots
                        $response = Http::timeout(3)->get($url . '/api/varieties/' . $slug);
                        
                        if ($response->successful()) {
                            $detail = $response->json('data');
                            if (!empty($detail['seed_lots']) && is_array($detail['seed_lots'])) {
                                // Filter seed lots yang aktif dijual dan memiliki quantity > 0
                                $sellableLots = array_filter($detail['seed_lots'], function ($lot) {
                                    return ($lot['is_sellable'] ?? false) && 
                                           ($lot['quantity'] ?? 0) > 0 && 
                                           ($lot['price_per_unit'] ?? 0) > 0;
                                });
                                
                                if (!empty($sellableLots)) {
                                    $prices = array_column($sellableLots, 'price_per_unit');
                                    $minPrice = min($prices);
                                    $maxPrice = max($prices);
                                    
                                    // Format price range
                                    if ($minPrice == $maxPrice) {
                                        return 'Rp ' . number_format($minPrice, 0, ',', '.');
                                    } else {
                                        return 'Rp ' . number_format($minPrice, 0, ',', '.') . 
                                           ' - Rp ' . number_format($maxPrice, 0, ',', '.');
                                    }
                                }
                            }
                        }
                        
                        // Return null jika tidak ada harga yang tersedia
                        return null;
                        
                    } catch (\Exception $e) {
                        // Jika gagal ambil detail, return null
                        return null;
                    }
                });
                
                $v['price_range_text'] = $priceRangeText;
                return $v;
            }, $varieties);

            /* =====================================================
            | FILTER BY COMMODITY (Client Side)
            ===================================================== */
            if (!empty($activeCommoditySlug)) {
                $varieties = array_values(array_filter($varieties, function ($v) use ($activeCommoditySlug) {
                    return strtolower($v['commodity']['slug'] ?? '') === $activeCommoditySlug;
                }));
            }

            /* =====================================================
            | SEARCH FILTER (Client Side)
            ===================================================== */
            if (!empty($search)) {
                $varieties = array_values(array_filter($varieties, function ($v) use ($search) {
                    return stripos($v['name'], $search) !== false
                        || stripos($v['commodity']['name'] ?? '', $search) !== false;
                }));
            }

            // Fallback for stock_by_class if it wasn't built (e.g. fetching all)
            // The previous logic tried to build it from seed_lots, but seed_lots is missing in index API.
            // So we leave it empty or null, and the view should handle it.
            $varieties = array_map(function ($v) {
                if (!isset($v['stock_by_class'])) {
                    $v['stock_by_class'] = [];
                }
                return $v;
            }, $varieties);


            /* =====================================================
            | RETURN VIEW
            ===================================================== */
            if (request()->ajax()) {
                return view('partials.katalog-grid', [
                    'varieties' => $varieties,
                ]);
            }

            return view('katalog', [
                'varieties'        => $varieties,
                'commodities'      => $commodities,
                'seedClasses'      => $seedClasses,
                'activeCommodity'  => $activeCommoditySlug,
                'activeSeedClass'  => $activeSeedClassCode,
                'searchKeyword'    => $search,
            ]);

        } catch (ConnectionException $e) {
            return response()->view('errors.server-busy', [], 503);
        }
    }


    public function getSeedLots($varietyId, $seedClassId)
    {
        $url = config('app.url_dev_admin');
        try {
            $response = Http::timeout(5)->get($url . "/api/varieties/{$varietyId}/seed-classes/{$seedClassId}/seed-lots");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch seed lots'], 500);
        }
    }

    // ======================================================
    // 🔍 AJAX Search Suggest (pakai cache lama)
    // ======================================================
    public function searchSuggest()
    {
        $keyword = request()->query('q');

        if (!$keyword) {
            return response()->json([]);
        }

        // Ambil dari cache yang panjang
        $varieties = Cache::get('varieties_all') ?? [];
        $commodities = Cache::get('commodities_all') ?? [];

        $results = [];

        // 🔹 MATCH VARIETAS
        foreach ($varieties as $v) {
            if (stripos($v['name'], $keyword) !== false) {
                $results[] = [
                    'type' => 'Varietas',
                    'name' => $v['name'],
                    'slug' => $v['slug'] ?? null,
                    'url'  => route('product.detail', $v['slug']), // otomatis buka detail
                ];
            }
        }

        // 🔹 MATCH KOMODITAS
        foreach ($commodities as $c) {
            if (stripos($c['name'], $keyword) !== false) {
                $results[] = [
                    'type' => 'Komoditas',
                    'name' => $c['name'],
                    'slug' => $c['slug'] ?? null,
                    'url'  => url('/katalog?commodity=' . strtolower($c['slug'])),
                ];
            }
        }

        return response()->json($results);
    }

    // ======================================================
    // 🔎 Pencarian submit form
    // ======================================================
    public function search()
    {
        $keyword = request()->query('search');

        if (!$keyword) {
            return redirect()->route('katalog');
        }

        $varieties = Cache::get('varieties_all') ?? [];
        $commodities = Cache::get('commodities_all') ?? [];

        // Exact match VARIETAS
        $matchVariety = collect($varieties)->first(function ($v) use ($keyword) {
            return strcasecmp($v['name'], $keyword) === 0;
        });

        if ($matchVariety) {
            return redirect()->route('product.detail', $matchVariety['slug']);
        }

        // Exact match KOMODITAS
        $matchCommodity = collect($commodities)->first(function ($c) use ($keyword) {
            return strcasecmp($c['name'], $keyword) === 0;
        });

        if ($matchCommodity) {
            return redirect('/katalog?commodity=' . strtolower($matchCommodity['slug']));
        }

        // Jika tidak ada exact → tampilkan hasil pencarian di katalog
        return redirect('/katalog?search=' . urlencode($keyword));
    }

    public function productDetail($slug)
    {
        $url = config('app.url_dev_admin');
        $response = Http::timeout(5)->get($url . '/api/varieties/' . $slug);

        if (!$response->successful()) {
            abort(404);
        }

        $variety = $response->json('data');

        // Fetch Seed Classes untuk mapping ID (karena API variety detail tidak menyertakan seed_class_id)
        $seedClasses = Cache::remember('seed_classes_all', 3600, function () use ($url) {
            return Http::timeout(5)->get($url . '/api/seed-classes')->json('data') ?? [];
        });

        $varietyInfo = \App\Services\VarietyInfoService::matchVarietyDetails($variety) ?? null;
        $varietyAudience = \App\Services\VarietyInfoService::summarizeByName($variety['name'] ?? '') ?? null;

        return view('produk.detail', [
            'variety' => $variety,
            'seedClasses' => $seedClasses,
            'varietyInfo' => $varietyInfo,
            'varietyAudience' => $varietyAudience,
        ]);
    }

    public function homeindex()
    {
        $url = config('app.url_dev_admin');

        try {
            $commodities = Cache::remember('commodities_all', 3600, function () use ($url) {
                $response = Http::timeout(5)->get($url . '/api/commodities');
                return $response->json('data') ?? [];
            });

            $allVarieties = Cache::remember('varieties_all', 1800, function () use ($url) {
                $response = Http::timeout(5)->get($url . '/api/varieties');
                return $response->json('data') ?? [];
            });

            $varieties = array_slice($allVarieties, 0, 8);

            $infoVarietas = \App\Services\VarietyInfoService::getAllVarieties(10000);

            return view('home', [
                'commodities' => $commodities,
                'varieties' => $varieties,
                'infoVarietas' => $infoVarietas,
            ]);

        } catch (ConnectionException $e) {
            return response()->view('errors.server-busy', [], 503);
        }
    }

    /**
     * Clear price cache for a specific variety
     */
    public function clearPriceCache($slug)
    {
        $cacheKey = "variety_price_{$slug}";
        Cache::forget($cacheKey);
        return response()->json(['success' => true, 'message' => 'Price cache cleared']);
    }

    /**
     * Clear all price caches
     */
    public function clearAllPriceCaches()
    {
        // Get all cache keys that start with variety_price_
        // Note: This is a simple implementation. For production, consider using cache tags
        $varieties = Cache::get('varieties_all') ?? [];
        $cleared = 0;
        
        foreach ($varieties as $variety) {
            $slug = $variety['slug'] ?? '';
            if (!empty($slug)) {
                $cacheKey = "variety_price_{$slug}";
                if (Cache::has($cacheKey)) {
                    Cache::forget($cacheKey);
                    $cleared++;
                }
            }
        }
        
        return response()->json([
            'success' => true, 
            'message' => "Price cache cleared for {$cleared} varieties"
        ]);
    }
}
