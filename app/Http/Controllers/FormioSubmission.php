<?php

namespace App\Http\Controllers;

use App\Models\Formio_submission;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class FormioSubmission extends BaseController
{
    public function all(){
        $forms = Formio_submission::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $forms
        ],200);
    }

    public function show($id){
        $forms = Formio_submission::with('formio_forms')->find($id);
        if (!$forms) {
            return response()->json([
                'message' => 'data tidak ditemukan',
            ]);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $forms
        ],201);
    }

    public function create(Request $request){
        $request['created_at']=time();
        $request['updated_at']=time();
        $forms = Formio_submission::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $forms
        ]);
    }

    public function update(Request $request, $id){
        $request['updated_at']=time();
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
        ],201);
    }

    public function hapus($id){
        $forms = Formio_submission::find($id);
        if (!$forms) {
            return response()->json([
                'message' => 'data tidak ditemukan',
            ]);
        }
        $forms->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ],200);
    }
}
