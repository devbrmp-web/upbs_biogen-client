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
        $filter = request()->query('commodity');
        $search = request()->query('search');
        $seedClassFilter = request()->query('seed_class'); // BS, FS, SS, ES

        try {
            // 🔸 Cache VARIETAS 30 menit
            $varieties = Cache::remember('varieties_all', 1800, function () use ($url) {
                $response = Http::timeout(5)->get($url . '/api/varieties');
                return $response->json('data') ?? [];
            });

            // 🔹 Filter SEED CLASS (Fetch varieties by Seed Class)
            if ($activeSeedClass = request('seed_class')) {
                // Ambil seed lots untuk seed class tertentu
                $seedLots = Http::get(config('app.url_dev_admin')."/api/seed-classes/{$activeSeedClass}/seed-lots")->json('data', []);
                    
                    if (empty($seedLots)) {
                         // Fallback logic: try to find class by code first, then fetch
                         // Because API above might expect ID, but we have code 'BS'
                         $seedClasses = Cache::remember('seed_classes_all', 3600, function () use ($url) {
                            return Http::timeout(5)->get($url . '/api/seed-classes')->json('data') ?? [];
                         });
                         
                         $classObj = collect($seedClasses)->firstWhere('code', $activeSeedClass);
                         
                         if ($classObj) {
                             $seedLots = Http::get(config('app.url_dev_admin')."/api/seed-classes/{$classObj['id']}/seed-lots")->json('data', []);
                         }
                    }

                    // Ekstrak variety_id unik
                    $varietyIds = array_unique(array_column($seedLots, 'variety_id'));
                
                if (empty($varietyIds)) {
                    $varieties = [];
                } else {
                    // Ambil varietas berdasarkan variety_id
                    $varieties = Http::get(config('app.url_dev_admin').'/api/varieties', ['ids' => implode(',', $varietyIds)])->json('data', []);
                }
            }

            // 🔸 Cache KOMODITAS 1 jam
            $commodities = Cache::remember('commodities_all', 3600, function () use ($url) {
                $response = Http::timeout(5)->get($url . '/api/commodities');
                return $response->json('data') ?? [];
            });

            // 🔹 Filter KOMODITAS
            if ($filter) {
                $varieties = array_filter($varieties, function ($v) use ($filter) {
                    return strtolower($v['commodity']['slug'] ?? '') === strtolower($filter);
                });
            }

            // 🔹 Filter SEARCH
            if ($search) {
                $varieties = array_filter($varieties, function ($v) use ($search) {
                    return stripos($v['name'], $search) !== false
                        || stripos($v['commodity']['name'] ?? '', $search) !== false;
                });
            }



            return view('Katalog', [
                'varieties' => $varieties,
                'commodities' => $commodities,
                'activeCommodity' => $filter,
                'searchKeyword' => $search,
                'activeSeedClass' => $seedClassFilter,
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
