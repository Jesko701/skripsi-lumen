<?php

namespace App\Http\Controllers;

use App\Models\Article_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Controller as BaseController;
use Spatie\Async\Pool;

class ArticleCategory extends BaseController
{
    public function all()
    {
        $pool = Pool::create();
        $pool->add(function () {
            $data = Article_category::all();
            return $data;
        });
        $hasil = $pool->wait();
        $data = $hasil[0];
        return response()->json([
            'message' => 'Data berhasil ditemukan',
            'data' => $data
        ]);
    }

    public function dataPagination(Request $request)
    {
        try {
            // Membuat kunci unik berdasarkan parameter request
            $cacheKey = 'dataPaginationCategory_' . md5(json_encode($request->all()));

            $cachedData = Cache::remember($cacheKey, 60, function () use ($request) {
                $page = $request->query('page', 1);
                $jumlah = (int)$request->query('jumlah', 50);
                $offset = ($page - 1) * $jumlah;

                $data = Article_category::with([
                    'article' => function ($query) use ($jumlah) {
                        $query->limit($jumlah);
                    }
                ])->skip($offset)->take($jumlah)->get();
                $totalData = Article_category::count();
                return [
                    'data' => $data,
                    'total_data' => $totalData
                ];
            });
            return response()->json([
                'message' => 'data berhasil ditemukan',
                'data' => $cachedData['data'],
                'total_data' => $cachedData['total_data']
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
