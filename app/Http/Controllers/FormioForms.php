<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Formio_forms;
use Illuminate\Http\Request;

class FormioForms extends BaseController
{
    public function all()
    {
        $forms = Formio_forms::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $forms
        ], 200);
    }

    public function dataPagination(Request $request)
    {
        $page = $request->input('page', 1);
        $jumlah = (int)$request->input('jumlah', 50);
        $offset = ($page - 1) * $jumlah;

        try {
            $data = Formio_forms::with(['formio_submission' => function ($query) use ($jumlah) {
                $query->take($jumlah);
            }])->skip($offset)->take($jumlah)->get();

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
        $forms = Formio_forms::with('formio_submission')->find($id);
        if (!$forms) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
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
        $forms = Formio_forms::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $forms
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request['updated_at'] = time();
        $forms = Formio_forms::find($id);
        if (!$forms) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $forms->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $forms
        ], 201);
    }

    public function hapus($id)
    {
        $forms = Formio_forms::find($id);
        if (!$forms) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $forms->formio_submission()->delete();
        $forms->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ], 200);
    }
}
