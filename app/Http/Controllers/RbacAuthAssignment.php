<?php

namespace App\Http\Controllers;

use App\Models\Rbac_auth_assignment;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RbacAuthAssignment extends BaseController
{
    public function all()
    {
        $assignment = Rbac_auth_assignment::all();
        $totalData = $assignment->count();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'total_data' => $totalData,
            'data' => $assignment
        ], 200);
    }

    public function dataPagination(Request $request)
    {
        $page = $request->input('page', 1);
        $jumlah = (int)$request->input('jumlah', 50);
        $offset = ($page - 1) * $jumlah;

        try {
            $data = Rbac_auth_assignment::with([
                'rbac_auth_item'
            ])
                ->skip($offset)
                ->take($jumlah)
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

    public function show($user_id)
    {
        $assignment = Rbac_auth_assignment::with('rbac_auth_item')->find($user_id);
        if (!$assignment) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $assignment
        ], 201);
    }

    public function create(Request $request)
    {
        $request['created_at'] = time();
        $assignment = Rbac_auth_assignment::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $assignment
        ], 201);
    }

    public function update(Request $request, $user_id)
    {
        $assignment = Rbac_auth_assignment::find($user_id);
        if (!$assignment) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $assignment->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate'
        ], 201);
    }

    public function hapus($user_id)
    {
        $assignment = Rbac_auth_assignment::find($user_id);
        if (!$assignment) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $assignment->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ], 200);
    }
}
