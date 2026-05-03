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
            | FETCH VARIETIES (SERVER-SIDE FILTERING)
            ===================================================== */
            $trait = request()->query('trait');
            $queryParams = [];
            if ($search) $queryParams['search'] = $search;
            if ($trait) $queryParams['trait'] = $trait;
            if ($activeCommoditySlug) $queryParams['commodity'] = $activeCommoditySlug;

            $hasFilters = !empty($search) || !empty($trait);
            $cacheKeySuffix = $hasFilters ? '_' . md5(json_encode($queryParams)) : '';

            if ($activeSeedClassId) {
                $cacheKey = "varieties_by_class_{$activeSeedClassId}{$cacheKeySuffix}";
                $endpoint = $url . "/api/seed-classes/{$activeSeedClassId}/varieties";
            } else {
                $cacheKey = "varieties_all{$cacheKeySuffix}";
                $endpoint = $url . '/api/varieties';
            }

            if ($forceRefresh || $hasFilters) {
                // Bypass cache if there are deep search filters
                $response = Http::timeout(5)->get($endpoint, $queryParams);
                $data = $response->successful() ? ($response->json('data') ?? []) : [];
                $varieties = array_map(function($item) {
                    if (!isset($item['images'])) $item['images'] = [];
                    if (!isset($item['price_range_text'])) $item['price_range_text'] = null;
                    if (!isset($item['stock_by_class'])) $item['stock_by_class'] = [];
                    return $item;
                }, $data);

                if (!$hasFilters) {
                    Cache::put($cacheKey, $varieties, $activeSeedClassId ? 300 : 1800);
                }
            } else {
                $varieties = Cache::remember($cacheKey, $activeSeedClassId ? 300 : 1800, function () use ($endpoint, $queryParams) {
                    $response = Http::timeout(5)->get($endpoint, $queryParams);
                    if ($response->successful()) {
                        $data = $response->json('data') ?? [];
                        return array_map(function($item) {
                            if (!isset($item['images'])) $item['images'] = [];
                            if (!isset($item['price_range_text'])) $item['price_range_text'] = null;
                            if (!isset($item['stock_by_class'])) $item['stock_by_class'] = [];
                            return $item;
                        }, $data);
                    }
                    return [];
                });
            }


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

        // Request API untuk search suggestion
        $url = config('app.url_dev_admin');
        $response = Http::timeout(5)->get($url . '/api/varieties', ['search' => $keyword]);
        $varieties = $response->successful() ? ($response->json('data') ?? []) : [];
        $commodities = Cache::get('commodities_all') ?? [];

        $results = [];

        // 🔹 MATCH VARIETAS
        foreach (array_slice($varieties, 0, 5) as $v) {
            $results[] = [
                'type' => 'Varietas',
                'name' => $v['name'],
                'slug' => $v['slug'] ?? null,
                'url'  => route('product.detail', $v['slug']), // otomatis buka detail
            ];
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

        return view('produk.detail', [
            'variety' => $variety,
            'seedClasses' => $seedClasses,
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
