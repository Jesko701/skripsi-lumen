<?php

namespace App\Http\Controllers;

use App\Models\Rbac_auth_item;
use App\Models\Rbac_auth_rule;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RbacAuthItem extends BaseController
{
    public function all(){
        $item = Rbac_auth_item::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $item
        ],200);
    }

    public function show($itemName){
        $item=Rbac_auth_item::with('rbac_auth_item_child','rbac_auth_assignment')->find($itemName);
        if (!$item) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $item
        ],200);
    }

    public function create(Request $request, $fkName){
        $rule = Rbac_auth_rule::find($fkName);
        if (!$rule) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $request['created_at'] = time();
        $request['updated_at'] = time();
        $item = Rbac_auth_item::create($request->all());
        return response()->json([
            'message' => 'data berhasil dibuat',
            'data' => $item
        ],201);
    }

    public function update(Request $request, $name){
        $item = Rbac_auth_item::find($name);
        if (!$item) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $request['updated_at'] = time();
        $item->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $item
        ],200);
    }

    public function hapus($id){
        $item = Rbac_auth_item::find($id);
        if (!$item) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $item->rbac_auth_item_child->delete();
        $item->rbac_auth_assignment->delete();
        $item->delete();
        return response()->json([
            'message' => 'data berhasil dihapus beserta relasinya'
        ],200);
    }

}
