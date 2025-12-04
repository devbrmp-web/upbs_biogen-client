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
        // Support both id and slug/code params for commodity & seed class
        $commodityId = request()->query('commodity_id');
        $commoditySlug = request()->query('commodity');
        $seedClassIdParam = request()->query('seed_class_id');
        $seedClassCodeParam = request()->query('seed_class');

        try {
            // Cache Seed Classes for dropdown
            $seedClasses = Cache::remember('seed_classes_all', 3600, function () use ($url) {
                return Http::timeout(5)->get($url . '/api/seed-classes')->json('data') ?? [];
            });

            // Cache Commodities for mapping id->slug and dropdown
            $commodities = Cache::remember('commodities_all', 3600, function () use ($url) {
                $response = Http::timeout(5)->get($url . '/api/commodities');
                return $response->json('data') ?? [];
            });

            // Resolve active seed class ID from either code or id
            $activeSeedClassId = null;
            $activeSeedClassCode = null;
            if (!empty($seedClassIdParam) && is_numeric($seedClassIdParam)) {
                $activeSeedClassId = (int) $seedClassIdParam;
                $match = collect($seedClasses)->firstWhere('id', $activeSeedClassId);
                $activeSeedClassCode = $match['code'] ?? null;
            } elseif (!empty($seedClassCodeParam)) {
                $match = collect($seedClasses)->firstWhere('code', $seedClassCodeParam);
                if ($match) {
                    $activeSeedClassId = $match['id'];
                    $activeSeedClassCode = $match['code'];
                }
            }

            // Resolve active commodity slug from either id or slug
            $activeCommoditySlug = null;
            if (!empty($commodityId) && is_numeric($commodityId)) {
                $c = collect($commodities)->firstWhere('id', (int) $commodityId);
                $activeCommoditySlug = strtolower($c['slug'] ?? '');
            } elseif (!empty($commoditySlug)) {
                $activeCommoditySlug = strtolower($commoditySlug);
            }

            // Build varieties list considering seed class first (narrower dataset)
            if (!empty($activeSeedClassId)) {
                $cacheKey = 'varieties_by_class_' . $activeSeedClassId;
                $varieties = Cache::remember($cacheKey, 600, function () use ($url, $activeSeedClassId) {
                    $resp = Http::timeout(5)->get($url . "/api/seed-classes/{$activeSeedClassId}/varieties");
                    return $resp->json('data') ?? [];
                });
            } else {
                // Fallback: all active varieties
                $varieties = Cache::remember('varieties_all', 1800, function () use ($url) {
                    $response = Http::timeout(5)->get($url . '/api/varieties');
                    return $response->json('data') ?? [];
                });
            }

            // Apply commodity filter if requested
            if (!empty($activeCommoditySlug)) {
                $varieties = array_values(array_filter($varieties, function ($v) use ($activeCommoditySlug) {
                    return strtolower($v['commodity']['slug'] ?? '') === $activeCommoditySlug;
                }));
            }

            // Apply search keyword if present
            if (!empty($search)) {
                $varieties = array_values(array_filter($varieties, function ($v) use ($search) {
                    return stripos($v['name'], $search) !== false
                        || stripos($v['commodity']['name'] ?? '', $search) !== false;
                }));
            }

            return view('Katalog', [
                'varieties' => $varieties,
                'commodities' => $commodities,
                'seedClasses' => $seedClasses,
                'activeCommodity' => $activeCommoditySlug,
                'searchKeyword' => $search,
                'activeSeedClass' => $activeSeedClassCode,
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

            return view('home', compact('commodities'));

        } catch (ConnectionException $e) {
            return response()->view('errors.server-busy', [], 503);
        }
    }
}
