<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\File_storage_item;

class FileStorageItem extends BaseController
{
    public function all(Request $request)
    {
        $page = $request->input('page', 1);
        $jumlah = $request->input('jumlah', 50);

        $offset = ($page - 1) * $jumlah;

        try {
            $getData = File_storage_item::select('*')->skip($offset)
                ->take($jumlah)
                ->get();

            if ($getData->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan'
                ], 404);
            } else {
                return response()->json([
                    'message' => 'Data berhasil ditemukan',
                    'data' => $getData,
                ], 200);
            }
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
