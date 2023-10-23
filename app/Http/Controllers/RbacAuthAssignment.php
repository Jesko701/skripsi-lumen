<?php

namespace App\Http\Controllers;

use App\Models\Rbac_auth_assignment;
use App\Models\Rbac_auth_item;
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

    public function show($fkName){
        $assignment = Rbac_auth_assignment::with('rbac_auth_item')->find($fkName);
        if (!$assignment){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
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

    public function update(Request $request, $fkName){
        $item = Rbac_auth_item::find($fkName);
        if (!$item) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $assignment = Rbac_auth_assignment::find($fkName);
        $assignment->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate'
        ],201);
    }

    public function delete($fkName){
        $assignment = Rbac_auth_assignment::find($fkName);
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
