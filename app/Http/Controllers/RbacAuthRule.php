<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Rbac_auth_rule;
use Illuminate\Http\Request;

class RbacAuthRule extends BaseController
{
    public function all()
    {
        $authRules = Rbac_auth_rule::all();
        // Memproses blob data sebelum mengirimkannya sebagai JSON
        $processedAuthRules = $authRules->map(function ($authRule) {
            return [
                'name' => $authRule->name,
                'data' => $authRule->formatted_data,
                'created_at' => $authRule->created_at,
                'updated_at' => $authRule->updated_at,
            ];
        });
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $processedAuthRules
        ], 200);
    }

    public function show($name)
    {
        $authRule = Rbac_auth_rule::with('rbac_auth_item')->find($name);
        if (!$authRule) {
            return response()->json([
                'message' => 'data tidak ditemuakan',
            ], 404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $authRule
        ], 200);
    }

    public function create(Request $request)
    {
        $request['created_at'] = time();
        $request['updated_at'] = time();
        $authRule = Rbac_auth_rule::create($request->all());
        return response()->json([
            'message' => 'data berhasil dibuat',
            'data' => $authRule
        ], 201);
    }

    public function update(Request $request, $name)
    {
        $request['updated_at'] = time();
        $authRule = Rbac_auth_rule::find($name);
        if (!$authRule) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 201);
        }
        $authRule->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $authRule
        ], 200);
    }

    public function hapus($name)
    {
        $authRule = Rbac_auth_rule::find($name);
        if (!$authRule) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 201);
        }
        $authRule->rbac_auth_item()->delete();
        $authRule->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ], 200);
    }
}
