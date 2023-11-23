<?php

namespace App\Http\Controllers;

use App\Models\Article as ModelArticle;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Article extends BaseController
{
    public function all()
    {
        $article = ModelArticle::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $article
        ], 200);
    }

    public function dataPagination (Request $request) {
        $page = $request->input('page', 1);
        $jumlah = (int)$request->input('jumlah', 50);
        $offset = ($page - 1) * $jumlah;
    
        $data = ModelArticle::with([
            'article_attachment' => function ($query) use ($jumlah) {
                $query->limit($jumlah);
            },
            'article_category'
        ])->skip($offset)->take($jumlah)->get();
    
        $totalData = ModelArticle::count(); // Jumlah total data
    
        return response()->json([
            'message' => 'Data berhasil ditemukan',
            'data' => $data,
            'total_data' => $totalData
        ], 200);
    }

    public function show($id)
    {
        $article = ModelArticle::with('article_category', 'article_attachment')->find($id);
        if (!$article) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'message' => 'data berhasil ditemukan',
            'data' => $article
        ]);
    }
    public function create(Request $request)
    {
        $request['created_at'] = time();
        $request['updated_at'] = time();
        $article =  ModelArticle::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $article
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request['updated_at'] = time();
        $article = ModelArticle::find($id);
        if (!$article) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        $this->validate($request, [
            'slug' => 'required|unique:article,slug,' . $id,
            'title' => 'required',
            'body' => 'required',
        ]);
        $article->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $article
        ]);
    }

    public function hapus($id)
    {
        $article = ModelArticle::find($id);
        if (!$article) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }

        $article->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
