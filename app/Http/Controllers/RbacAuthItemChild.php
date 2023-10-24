<?php

namespace App\Http\Controllers;

use App\Models\Rbac_auth_item;
use App\Models\Rbac_auth_item_child;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RbacAuthItemChild extends BaseController
{
    public function all()
    {
        $itemChild = Rbac_auth_item_child::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $itemChild
        ], 200);
    }

    public function show($parentOrChild)
    {
        $itemChild = Rbac_auth_item_child::with('rbac_auth_item')->find($parentOrChild);
        if (!$itemChild) {
            return response()->json(['message' => "Data tidak ditemukan"], 404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $itemChild
        ], 201);
    }
    public function create(Request $request)
    {
        $itemChild = Rbac_auth_item_child::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $itemChild
        ], 201);
    }

    public function update(Request $request, $parentOrChild){
        $item = Rbac_auth_item::find($parentOrChild);
        if (!$item){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $itemChild = Rbac_auth_item_child::find($parentOrChild);
        $itemChild->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $itemChild
        ],201);
    }
    public function hapus($parentOrChild){
        $itemChild = Rbac_auth_item_child::find($parentOrChild);
        if (!$itemChild){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $itemChild->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ],200);
    }
}
