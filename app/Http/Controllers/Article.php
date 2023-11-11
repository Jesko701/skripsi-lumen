<?php

namespace App\Http\Controllers;

use App\Models\Article as ModelArticle;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Amp\Loop;

class Article extends BaseController
{
    public function all()
    {
        try {
            $article = null;
            Loop::run(function () use (&$article) {
                $articlePromise = \Amp\call(function () {
                    return ModelArticle::all();
                });
                $article = yield $articlePromise;
            });
            return response()->json([
                'message' => 'berhasil mengambil seluruh data',
                'data' => $article
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function dataPagination(Request $request)
    {
        $page = $request->query('page', 1);
        $jumlah = $request->query('jumlah', 50);
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
        try {
            $article = null;
            Loop::run(function () use ($id, &$article) {
                $articlePromise = \Amp\call(function () use ($id) {
                    return ModelArticle::with('article_category', 'article_attachment')->find($id);
                });
                $article = \Amp\Promise\wait($articlePromise);
            });
            if (!$article) {
                return response()->json([
                    'message' => 'data tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'message' => 'data berhasil ditemukan',
                'data' => $article
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $th->getMessage(),
            ], 500);
        }
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
