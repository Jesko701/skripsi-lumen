<?php

namespace App\Http\Controllers;

use App\Models\Article_category;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ArticleCategory extends BaseController
{
    public function all()
    {
        $category = Article_category::all();
        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $category
        ], 200);
    }

    public function dataPagination(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $jumlah = (int)$request->input('jumlah', 50);
            $offset = ($page - 1) * $jumlah;

            $data = Article_category::skip($offset)
                ->take($jumlah)
                ->get();

            $data->load('article');

            $data->each(function ($category) use ($jumlah) {
                $category->setRelation(
                    'article',
                    $category->article->take($jumlah)
                );
            });

            return response()->json([
                'message' => 'data berhasil ditemukan',
                'data' => $data,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Terjadi saat mengambil data',
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $category = Article_category::with('article')->find($id);
        if (!$category) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json([[
            'message' => 'data berhasil ditemukan',
            'data' => $category
        ]], 200);
    }

    public function create(Request $request)
    {
        $request['created_at'] = time();
        $request['updated_at'] = time();
        $category = Article_category::create($request->all());
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $request['updated_at'] = time();
        $category = Article_category::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'data tidak ditemuakan'
            ]);
        }
        $category->update($request->all());
        Article_category::where('parent_id', $id)->update(['parent_id' => $category->id]);
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $category
        ], 200);
    }

    public function hapus($id)
    {
        $category = Article_category::find($id);
        if (!$category) {
            return response()->json([
                'message' => "data tidak ditemukan",
            ]);
        }
        Article_category::where('parent_id', $id)->delete();
        $category->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ], 200);
    }
}
