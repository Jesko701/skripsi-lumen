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

    public function show($parent)
    {
        $itemChild = Rbac_auth_item_child::with('rbac_auth_item')->find($parent);
        if (!$itemChild) {
            return response()->json(['message' => "Data tidak ditemukan"], 404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $itemChild
        ], 200);
    }
    public function create(Request $request)
    {
        $itemChild = Rbac_auth_item_child::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $itemChild
        ], 201);
    }

    public function update(Request $request, $parent)
    {
        $column_parent = "parent";
        $column_child = "child";
        $item = Rbac_auth_item_child::where(function ($query) use ($parent, $column_parent, $column_child) {
            $query->where($column_parent, $parent)->orWhere($column_child, $parent);
        })->update(['child' => $request->json('child')]);
        if ($item === 0) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $updateRecord = Rbac_auth_item_child::where(function ($query) use ($parent, $column_parent, $column_child) {
            $query->where($column_parent, $parent)->orWhere($column_child, $parent);
        })->get();
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $updateRecord
        ], 200);
    }
    public function hapus($parent)
    {
        $column_parent = 'parent';
        $column_child = 'child';
        $itemChild = Rbac_auth_item_child::where(function ($query) use ($parent, $column_parent, $column_child) {
            $query->where($column_parent, $parent)->orWhere($column_child, $parent);
        })->delete();
        if ($itemChild > 0){
            return response()->json([
                'message' => 'data berhasil dihapus'
            ], 200);
        };
        return response()->json([
            'message' => 'data gagal dihapus'
        ],404);
    }
}
