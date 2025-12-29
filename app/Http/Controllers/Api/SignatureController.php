<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function store(Request $request, $orderCode)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);

        try {
            $signature = $request->input('signature');
            
            // Remove "data:image/png;base64,"
            if (preg_match('/^data:image\/(\w+);base64,/', $signature, $type)) {
                $signature = substr($signature, strpos($signature, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                
                if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                    return response()->json(['success' => false, 'message' => 'Invalid image type'], 400);
                }
                
                $signature = base64_decode($signature);
                
                if ($signature === false) {
                    return response()->json(['success' => false, 'message' => 'Base64 decode failed'], 400);
                }
            } else {
                 return response()->json(['success' => false, 'message' => 'Invalid data URI'], 400);
            }

            $imageName = 'signature_' . $orderCode . '_' . time() . '.png';
            
            // Store in public/signatures
            Storage::disk('public')->put('signatures/' . $imageName, $signature);

            // Generate URL
            $url = asset('storage/signatures/' . $imageName);

            // Send to Admin API
            $adminUrl = config('app.url_dev_admin');
            $response = Http::post("$adminUrl/api/orders/$orderCode/signature", [
                'signature_path' => $url
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'url' => $url]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Failed to sync with admin: ' . $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
