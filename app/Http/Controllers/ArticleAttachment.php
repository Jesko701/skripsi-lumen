<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Article_attachment;
use Illuminate\Http\Request;
use Amp\Loop;

class ArticleAttachment extends BaseController {
    public function all()
    {
        try {
            $attachment = null;

            Loop::run(function () use (&$attachment) {
                $attachmentPromise = \Amp\call(function () {
                    return Article_attachment::all();
                });
                $attachment = yield $attachmentPromise;
            });

            return response()->json([
                'message' => 'berhasil mengambil seluruh data',
                'data' => $attachment
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function dataPagination(Request $request)
    {
        $page = $request->input('page', 1);
        $jumlah = (int)$request->input('jumlah', 50);
        $offset = ($page - 1) * $jumlah;
        try {
            $data = Article_attachment::with('article')->skip($offset)->take($jumlah)->get();
            return response()->json([
                'message' => 'Data berhasil ditemukan',
                'data' => $data
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $error->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        $attachment = Article_attachment::with('article')->find($id);
        if (!$attachment) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $attachment
        ], 201);
    }

    public function create(Request $request)
    {
        $request['created_at'] = time();
        $attachmentData = $request->all();
        $attachment = Article_attachment::create($attachmentData);
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $attachment
        ], 201);
    }

    public function update(Request $request, $id)
    {

        $attachment = Article_attachment::find($id);
        if (!$attachment) {
        if (!$attachment) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $attachment->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $attachment
        ], 200);
    }
 
    public function hapus($id){
        $attachment = Article_attachment::find($id);
        if (!$attachment) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $attachment->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ], 200);
    }
}
