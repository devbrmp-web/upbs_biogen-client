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
            $seedClasses = Cache::remember('seed_classes_all', 3600, function () use ($url) {
                return Http::timeout(5)
                    ->get($url . '/api/seed-classes')
                    ->json('data') ?? [];
            });

            $commodities = Cache::remember('commodities_all', 3600, function () use ($url) {
                return Http::timeout(5)
                    ->get($url . '/api/commodities')
                    ->json('data') ?? [];
            });

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
            | FETCH VARIETIES (PRIORITIZE SEED CLASS)
            ===================================================== */
            if (!empty($activeSeedClassId)) {
                $cacheKey = "varieties_by_seed_class_{$activeSeedClassId}";
                $varieties = Cache::remember($cacheKey, 600, function () use ($url, $activeSeedClassId) {
                    return Http::timeout(5)
                        ->get($url . "/api/seed-classes/{$activeSeedClassId}/varieties")
                        ->json('data') ?? [];
                });
            } else {
                $varieties = Cache::remember('varieties_all', 1800, function () use ($url) {
                    return Http::timeout(5)
                        ->get($url . '/api/varieties')
                        ->json('data') ?? [];
                });
            }

            /* =====================================================
            | FILTER BY COMMODITY
            ===================================================== */
            if (!empty($activeCommoditySlug)) {
                $varieties = array_values(array_filter($varieties, function ($v) use ($activeCommoditySlug) {
                    return strtolower($v['commodity']['slug'] ?? '') === $activeCommoditySlug;
                }));
            }

            /* =====================================================
            | SEARCH FILTER
            ===================================================== */
            if (!empty($search)) {
                $varieties = array_values(array_filter($varieties, function ($v) use ($search) {
                    return stripos($v['name'], $search) !== false
                        || stripos($v['commodity']['name'] ?? '', $search) !== false;
                }));
            }

            /* =====================================================
            | BUILD STOCK BY SEED CLASS (CORE FIX)
            ===================================================== */
            $varieties = array_map(function ($v) {

                $stockByClass = [];

                foreach (($v['seed_lots'] ?? []) as $lot) {

                    if (
                        !($lot['is_sellable'] ?? false) ||
                        ($lot['quantity'] ?? 0) <= 0 ||
                        empty($lot['seed_class']['code'])
                    ) {
                        continue;
                    }

                    $code = $lot['seed_class']['code'];
                    $name = $lot['seed_class']['name'] ?? $code;

                    if (!isset($stockByClass[$code])) {
                        $stockByClass[$code] = [
                            'name'  => $name,
                            'stock' => 0,
                        ];
                    }

                    $stockByClass[$code]['stock'] += (int) $lot['quantity'];
                }

                // inject ke varietas
                $v['stock_by_class'] = $stockByClass;

                return $v;

            }, $varieties);

            /* =====================================================
            | RETURN VIEW
            ===================================================== */
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

        return view('produk.detail', compact('variety', 'seedClasses'));
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

            return view('home', [
                'commodities' => $commodities,
                'varieties' => $varieties,
            ]);

        } catch (ConnectionException $e) {
            return response()->view('errors.server-busy', [], 503);
        }
    }
}
