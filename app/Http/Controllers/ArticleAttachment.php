<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Article_attachment;
use Illuminate\Http\Request;

class ArticleAttachment extends BaseController
{
    public function all()
    {
        $attachment = Article_attachment::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $attachment
        ], 200);
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

    public function hapus($id)
    {
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
