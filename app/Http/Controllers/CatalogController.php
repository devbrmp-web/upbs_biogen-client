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
        try {
            // Ambil daftar varietas
            $varietiesResponse = Http::timeout(5)->get($url_dev_admin . '/api/varieties');
            $varieties = $varietiesResponse->json('data') ?? [];

            // Ambil daftar komoditas
            $commoditiesResponse = Http::timeout(5)->get($url_dev_admin . '/api/commodities');
            $commodities = $commoditiesResponse->json('data') ?? [];

            // Kirim dua variabel ke view
            return view('Katalog', [
                'varieties' => $varieties,
                'commodities' => $commodities
            ]);

        } catch (ConnectionException $e) {
            // Jika gagal koneksi API → tampilkan halaman custom
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
