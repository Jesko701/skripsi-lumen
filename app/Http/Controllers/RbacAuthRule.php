<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Rbac_auth_rule;
use Illuminate\Http\Request;

class RbacAuthRule extends BaseController
{
    public function all(){
        $authRule = Rbac_auth_rule::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $authRule
        ],200);
    }

    public function show($id){
        $authRule = Rbac_auth_rule::with('rbac_auth_item')->find($id);
        if (!$authRule){
            return response()->json([
                'message' => 'data tidak ditemuakan',
            ],404);
        }
        return response()->json([
            'message' => 'data ditemukan',
            'data' => $authRule
        ],201);
    }

    public function create(Request $request){
        $request['created_at'] = time();
        $request['updated_at'] = time();
        $authRule = Rbac_auth_rule::create($request->all());
        return response()->json([
            'message' => 'data berhasil dibuat',
            'data' => $authRule
        ],201);
    }

    public function update(Request $request, $id){
        $request['updated_at'] = time();
        $authRule = Rbac_auth_rule::find($id);
        if (!$authRule){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],201);
        }
        $authRule->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $authRule
        ],201);
    }

    public function delete($id){
        $authRule = Rbac_auth_rule::find($id);
        if (!$authRule){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],201);
        }
        $authRule->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ],200);
    }
}
