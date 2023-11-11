<?php

namespace App\Http\Controllers;

use App\Models\Rbac_auth_item;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RbacAuthItem extends BaseController
{
    public function all()
    {
        $item = Rbac_auth_item::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $item
        ], 200);
    }

    public function dataPagination(Request $request)
    {
        $page = $request->query('page', 1);
        $jumlah = $request->query('jumlah', 50);
        $offset = ($page - 1) * $jumlah;

        try {
            $data = Rbac_auth_item::with([
                'rbac_auth_item_children',
                'rbac_auth_assignment' => function ($query) use ($jumlah) {
                    $query->take($jumlah);
                }
            ])
                ->offset($offset)
                ->limit($jumlah)
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan'
                ], 404);
            } else {
                return response()->json([
                    'message' => 'Data berhasil ditemukan',
                    'data' => $data,
                ], 200);
            }
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $error->getMessage(),
            ], 500);
        }
    }



    public function show($name)
    {
        $item = Rbac_auth_item::with('rbac_auth_item_children', 'rbac_auth_assignment')->find($name);
        if (!$item) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $item
        ], 200);
    }

    public function create(Request $request)
    {
        $request['created_at'] = time();
        $request['updated_at'] = time();
        $item = Rbac_auth_item::create($request->all());
        return response()->json([
            'message' => 'data berhasil dibuat',
            'data' => $item
        ], 201);
    }

    public function update(Request $request, $name)
    {
        $item = Rbac_auth_item::find($name);
        if (!$item) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $request['updated_at'] = time();
        $item->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $item
        ], 200);
    }

    public function hapus($name)
    {
        $item = Rbac_auth_item::find($name);
        if (!$item) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $item->delete();
        return response()->json([
            'message' => 'data berhasil dihapus beserta relasinya'
        ], 200);
    }
}
