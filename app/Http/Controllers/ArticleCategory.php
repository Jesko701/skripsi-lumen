<?php

namespace App\Http\Controllers;

use App\Models\Article_category;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Amp\Loop;

class ArticleCategory extends BaseController
{
    public function all()
    {
        $categoryData = [];

        Loop::run(function () use (&$categoryData) {
            $promises = [];
            // Tambahkan setiap panggilan database sebagai promise
            $promises['categories'] = \Amp\call(function () {
                return Article_category::all()->toArray();
            });
            $results = yield $promises;
            $categoryData = $results['categories'];
        });

        return response()->json([
            'message' => 'berhasil mengambil seluruh data',
            'data' => $categoryData
        ], 200);
    }

    public function dataPagination(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $jumlah = (int)$request->query('jumlah', 50);
            $offset = ($page - 1) * $jumlah;

            $data = null;
            $totalData = null;

            Loop::run(function () use ($jumlah, $offset, &$data, &$totalData) {
                $promise = [];
                $promise['categories'] = \Amp\call(function () use ($jumlah, $offset) {
                    return Article_category::with([
                        'article' => function ($query) use ($jumlah) {
                            $query->limit($jumlah);
                        }
                    ])->skip($offset)->take($jumlah)->get()->toArray();
                });
                $promise['totalData'] = \Amp\call(
                    function () {
                        return Article_category::count();
                    }
                );

                $result = yield $promise;
                $data = $result['categories'];
                $totalData = $result['totalData'];
            });

            return response()->json([
                'message' => 'Data berhasil ditemukan',
                'data' => $data,
                'total_data' => $totalData,
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
        try {
            $categoryData = null;

            Loop::run(function () use ($id, &$categoryData) {
                $categoryPromise = \Amp\call(function () use ($id) {
                    return Article_category::with('article')->find($id);
                });

                $categoryData = yield $categoryPromise;
            });

            if (!$categoryData) {
                return response()->json([
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'message' => 'Data berhasil ditemukan',
                'data' => $categoryData
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $error->getMessage(),
            ], 500);
        }
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
