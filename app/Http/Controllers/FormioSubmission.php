<?php

namespace App\Http\Controllers;

use App\Models\Formio_submission;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class FormioSubmission extends BaseController
{
    public function all(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $itemsPerPage = 75;
            $offset = ($page - 1) * $itemsPerPage;
            $data = Formio_submission::select('*')->offset($offset)->limit($itemsPerPage)->get();
            return response()->json([
                'message' => 'data berhasil ditemukan',
                'data' => $data,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    public function dataPagination(Request $request)
    {
        $page = $request->input('page', 1);
        $jumlah = $request->input('jumlah', 50);
        $offset = ($page - 1) * $jumlah;

        try {
            $data = Formio_submission::with('formio_forms')
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
                    'data' => $data
                ], 200);
            }
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $error->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        $forms = Formio_submission::with('formio_forms')->find($id);
        if (!$forms) {
            return response()->json([
                'message' => 'data tidak ditemukan',
            ]);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $forms
        ], 201);
    }

    public function create(Request $request)
    {
        $request['created_at'] = time();
        $request['updated_at'] = time();
        $forms = Formio_submission::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $forms
        ]);
    }

    public function update(Request $request, $id)
    {
        $request['updated_at'] = time();
        $forms = Formio_submission::find($id);
        if (!$forms) {
            return response()->json([
                'message' => 'data tidak ditemukan',
            ]);
        }
        $forms->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $forms
        ], 201);
    }

    public function hapus($id)
    {
        $forms = Formio_submission::find($id);
        if (!$forms) {
            return response()->json([
                'message' => 'data tidak ditemukan',
            ]);
        }
        $forms->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ], 200);
    }
}
