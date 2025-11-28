<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
   public function catalogindex()
{
    $url_dev_admin = config('app.url_dev_admin');
    $filter = request()->query('commodity'); // baca ?commodity=...

    try {
        // Ambil semua varietas dari API
        $varietiesResponse = Http::timeout(5)->get($url_dev_admin . '/api/varieties');
        $varieties = $varietiesResponse->json('data') ?? [];

        // Jika ada filter, lakukan penyaringan berdasarkan slug komoditas
        if ($filter) {
            $varieties = array_filter($varieties, function ($v) use ($filter) {
                return strtolower($v['commodity']['slug'] ?? '') === strtolower($filter);
            });
        }

        // Ambil daftar komoditas
        $commoditiesResponse = Http::timeout(5)->get($url_dev_admin . '/api/commodities');
        $commodities = $commoditiesResponse->json('data') ?? [];

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
