<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class CatalogController extends Controller
{
    public function catalogindex()
    {
        $url_dev_admin = config('app.url_dev_admin');
        $filter = request()->query('commodity');

        try {

            //------------------------------------
            // 🔸 CACHE VARIETAS (30 detik)
            //------------------------------------
            $varieties = Cache::remember('varieties_all', 30, function () use ($url_dev_admin) {
                $response = Http::timeout(5)->get($url_dev_admin . '/api/varieties');
                return $response->json('data') ?? [];
            });

            // Filter jika ada ?commodity=
            if ($filter) {
                $varieties = array_filter($varieties, function ($v) use ($filter) {
                    return strtolower($v['commodity']['slug'] ?? '') === strtolower($filter);
                });
            }

            //------------------------------------
            // 🔸 CACHE KOMODITAS (5 menit)
            //------------------------------------
            $commodities = Cache::remember('commodities_all', 300, function () use ($url_dev_admin) {
                $response = Http::timeout(5)->get($url_dev_admin . '/api/commodities');
                return $response->json('data') ?? [];
            });


            return view('Katalog', [
                'varieties' => $varieties,
                'commodities' => $commodities,
                'activeCommodity' => $filter,
            ]);

        } catch (ConnectionException $e) {
            return response()->view('errors.server-busy', [], 503);
        }
    }

    public function homeindex()
    {
        $url_dev_admin = config('app.url_dev_admin');
        try {
            // Ambil data commodities dari API
            $response = Http::timeout(5)->get($url_dev_admin . '/api/commodities');
            $commodities = $response->json('data') ?? [];

            return view('home', compact('commodities'));

        } catch (ConnectionException $e) {
            return response()->view('errors.server-busy', [], 503);
        }
    }
}
