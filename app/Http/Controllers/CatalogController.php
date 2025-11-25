<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class CatalogController extends Controller
{
    public function catalogindex()
    {
        try {
            // Ambil daftar varietas
            $varietiesResponse = Http::timeout(5)->get('http://localhost:8000/api/varieties');
            $varieties = $varietiesResponse->json('data') ?? [];

            // Ambil daftar komoditas
            $commoditiesResponse = Http::timeout(5)->get('http://localhost:8000/api/commodities');
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
        try {
            // Ambil data commodities dari API
            $response = Http::timeout(5)->get('http://localhost:8000/api/commodities');
            $commodities = $response->json('data') ?? [];

            return view('home', compact('commodities'));

        } catch (ConnectionException $e) {
            return response()->view('errors.server-busy', [], 503);
        }
    }
}
