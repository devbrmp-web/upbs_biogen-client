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

        try {
            // 🔸 Cache VARIETAS 30 menit
            $varieties = Cache::remember('varieties_all', 1800, function () use ($url) {
                $response = Http::timeout(5)->get($url . '/api/varieties');
                return $response->json('data') ?? [];
            });

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
            ]);

        } catch (ConnectionException $e) {
            return response()->view('errors.server-busy', [], 503);
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
        return view('produk.detail', compact('variety'));
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
