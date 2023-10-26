<?php

namespace App\Http\Controllers;

use App\Models\Rbac_auth_assignment;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RbacAuthAssignment extends BaseController
{
    public function all(){
        $assignment = Rbac_auth_assignment::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $assignment
        ],200);
    }

    public function show($item_name){
        $assignment = Rbac_auth_assignment::with('rbac_auth_item')->find($item_name);
        if (!$assignment){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $assignment
        ],201);
    }

    public function create(Request $request){
        $request['created_at'] = time();
        $assignment = Rbac_auth_assignment::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $assignment
        ],201);
    }

    public function update(Request $request, $item_name){
        $assignment = Rbac_auth_assignment::find($item_name);
        if (!$assignment){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $assignment->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate'
        ],201);
    }

    public function hapus($item_name){
        $assignment = Rbac_auth_assignment::find($item_name);
        if (!$assignment) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $assignment->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ],200);
    }
}
