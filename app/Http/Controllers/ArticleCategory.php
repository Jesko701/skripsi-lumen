<?php

namespace App\Http\Controllers;

use App\Models\Article_category;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Amp\Loop;
use Amp\Promise as AmpPromise;
use React\EventLoop\Factory;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use React\EventLoop\Loop as ReactLoop;


class ArticleCategory extends BaseController
{
    public function all()
    {
        $data = null;
        $totalData = null;
        Loop::run(function () use (&$data, &$totalData) {
            $dataPromise = (function () {
                return Article_category::all();
            });

            $totalDataPromise =(function () {
                return Article_category::count();
            });

            $result = yield AmpPromise\all([$dataPromise, $totalDataPromise]);

            $data = $result[0];
            $totalData = $result[1];
        });

        return response()->json([
            'message' => 'Data berhasil ditemukan',
            'data' => $data,
            'total_data' => $totalData,
        ], 200);
    }

    public function allSync(){
        $data = Article_category::all();
        $totalData = Article_category::count();
        return response()->json([
            'message' => 'Data berhasil ditemukan',
            'data' => $data,
            'total_data' => $totalData,
        ], 200);
    }

    public function allReactPhp(Request $request)
    {
        $page = $request->query('page', 1);
        $jumlah = (int) $request->query('jumlah', 50);
        $offset = ($page - 1) * $jumlah;

        $loop = Factory::create();

        $dataPromise = $this->fetchDataAsync($jumlah, $offset, $loop);
        $totalDataPromise = $this->fetchTotalDataAsync($loop);

        $loop->run();

        $dataPromise->then(
            function ($data) use ($totalDataPromise) {
                $totalDataPromise->then(
                    function ($totalData) use ($data) {
                        return response()->json([
                            'message' => 'Data berhasil ditemukan',
                            'data' => $data,
                            'total_data' => $totalData,
                        ], 200);
                    },
                    function ($error) {
                        return response()->json([
                            'message' => 'Terjadi kesalahan saat mengambil total data',
                            'error' => $error->getMessage(),
                        ], 500);
                    }
                );
            },
            function ($error) {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat mengambil data',
                    'error' => $error->getMessage(),
                ], 500);
            }
        );
    }

    private function fetchDataAsync($jumlah, $offset, $loop): Promise
    {
        return new Promise(function (callable $resolve, callable $reject) use ($loop, $jumlah, $offset) {
            $loop->addTimer(1, function () use ($resolve, $jumlah, $offset) {
                $data = Article_category::skip($offset)->take($jumlah)->get()->toArray();
                $resolve($data);
            });
        });
    }

    private function fetchTotalDataAsync($loop): Promise
    {
        return new Promise(function (callable $resolve, callable $reject) use ($loop) {
            $loop->addTimer(1, function () use ($resolve) {
                $totalData = Article_category::count();
                $resolve($totalData);
            });
        });
    }

    public function dataPagination(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $jumlah = (int)$request->query('jumlah', 50);
            $offset = ($page - 1) * $jumlah;

            $data = Article_category::with([
                'article' => function ($query) use ($jumlah) {
                    $query->limit($jumlah);
                }
            ])->skip($offset)->take($jumlah)->get();

            $totalData = Article_category::count();

            return response()->json([
                'message' => 'data berhasil ditemukan',
                'data' => $data,
                'total_data' => $totalData
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
