<?php

namespace App\Http\Controllers;

use App\Models\Article_category;
use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Controller as BaseController;
use React\MySQL\Factory;
use React\MySQL\QueryResult;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use React\EventLoop\Loop;
use Exception;
use React\Promise\Deferred;
use React\Promise\Promise;

class ArticleCategory extends BaseController
{
    protected $databaseHost;
    protected $databaseName;
    protected $databaseUser;
    protected $databasePassword;
    protected $connector;

    public function __construct()
    {
        $this->databaseHost = env('DB_HOST');
        $this->databaseName = env('DB_DATABASE');
        $this->databaseUser = env('DB_USERNAME');
        $this->databasePassword = env('DB_PASSWORD');
        $factory = new Factory();
        $this->connector = $factory->createLazyConnection($this->databaseUser . ':' . $this->databasePassword . '@' . $this->databaseHost . '/' . $this->databaseName);
    }

    //     // Return the promise
    //     return $deferred->promise();
    // }

    // public function fetchData()
    // {
    //     $connection = $this->connector;

    //     $deferred = new Deferred();

    //     Loop::run(function () use ($connection, $deferred) {
    //         $connection->query('SELECT * FROM article_category')
    //             ->then(
    //                 function (QueryResult $command) use ($deferred) {
    //                     $dataFields = $command->resultFields;
    //                     $dataRows = $command->resultRows;

    //                     // Build an associative array from the result
    //                     $formattedData = [];
    //                     foreach ($dataRows as $row) {
    //                         $formattedRow = [];
    //                         foreach ($row as $key => $value) {
    //                             // Use column name as key
    //                             $formattedRow[$dataFields[$key]['name']] = $value;
    //                         }
    //                         $formattedData[] = $formattedRow;
    //                     }

    //                     // Resolve the deferred with the formatted data
    //                     $deferred->resolve($formattedData);
    //                 },
    //                 function (Exception $err) use ($deferred) {
    //                     // Reject the deferred with an error
    //                     $deferred->reject($err);
    //                 }
    //             );
    //     });

    //     // Yield the promise to wait for it to resolve
    //     yield $deferred->promise();
    // }
    public function all()
    {
        $connection = $this->connector;
        // * Menggunakan Pool

        // ! coba pakai method newFetchData(gagal)
        // return $this->newfetchData()->then(
        //     function ($result) {
        //         return response()->json([
        //             'message' => 'data berhasil diambil',
        //             'data' => $result
        //         ]);
        //     },
        //     function ($error) {
        //         return response()->json([
        //             'message' => 'terjadi kesalahan dalam mengambil data di database',
        //             'error' => $error
        //         ], 500);
        //     }
        // );
        // ! mencoba printing data (Gagal) 
        // $result = yield $this->fetchData();
        // return response()->json([
        //     'message' => 'data berhasil diambil',
        //     'data' => $result
        // ]);
        // ! Data tidak ke print
        // Loop::run(function () use ($connection) {
        //     $deferred = new Deferred();

        //     $connection->query('SELECT * FROM article_category')
        //         ->then(
        //             function (QueryResult $command) use ($deferred) {
        //                 $dataFields = $command->resultFields;
        //                 $dataRows = $command->resultRows;

        //                 // Build an associative array from the result
        //                 $formattedData = [];
        //                 foreach ($dataRows as $row) {
        //                     $formattedRow = [];
        //                     foreach ($row as $key => $value) {
        //                         // Use column name as key
        //                         $formattedRow[$dataFields[$key]['name']] = $value;
        //                     }
        //                     $formattedData[] = $formattedRow;
        //                 }

        //                 // Resolve the deferred with the formatted data
        //                 $deferred->resolve($formattedData);
        //             },
        //             function (Exception $err) use ($deferred) {
        //                 // Reject the deferred with an error
        //                 $deferred->reject($err);
        //             }
        //         );

        //     // Wait for the deferred to be resolved and get the result
        //     $result = yield $deferred->promise();

        //     // Return or output the result
        //     echo json_encode($result, JSON_PRETTY_PRINT);
        // });
        // * testing (keluar hasil tetapi berupa array)
        $deferred = new Deferred();
        $resultPromise = $deferred->promise($connection->query("SELECT * FROM article_category")->then(
            function (QueryResult $command) {
                // print_r($command->resultRows);
                // echo count($command->resultRows) . ' row(s) in set' . PHP_EOL;
                return json_encode($command->resultRows, JSON_PRETTY_PRINT);
            },
            function (Exception $err) {
                echo 'Error: ' . $err->getMessage() . PHP_EOL;
            }
        ));
        // $deferred->resolve($resultPromise);
                
        // // * Promise Fail
        // $query = "SELECT * FROM article_category";
        // $hasilData = null;
        // $this->ambilDataDariDatabase($query)
        //     ->then(function ($hasil) use (&$hasilData) {
        //         $hasilData = $hasil;
        //     })
        //     ->otherwise(function ($err) {
        //         return response()->json([
        //             'message' => 'terjadi kesalahan dalam mengambil data',
        //             'error' => $err->getMessage()
        //         ], 500);
        //     });

        // $connection->query("SELECT * FROM article_category")->then(
        //     function (QueryResult $command) {
        //         print_r($command->resultRows);
        //         echo count($command->resultRows) . ' row(s) in set' . PHP_EOL;
        //     },
        //     function (Exception $err) {
        //         echo 'Error: ' . $err->getMessage() . PHP_EOL;
        //     }
        // );

        // ! Tanpa menggunakan loop run & perlu membuat fungsi handle (tidak muncul)
        // $data = null;
        // $dataFile = null;

        // $deferred = new Deferred();

        // $connection->query('SELECT * FROM article_category')
        //     ->then(function (QueryResult $command) use (&$data, &$dataField, $deferred) {
        //         $data = $command->resultRows;
        //         $dataField = $command->resultFields;
        //         $deferred->resolve($data);
        //     }, function (\Throwable $reason) use ($deferred) {
        //         $exception = new \Exception($reason->getMessage(), $reason->getCode(), $reason);
        //         $deferred->reject($exception);
        //     });

        // $promise = $deferred->promise();
        // return $this->handlePromiseGetAllCategory($promise);

        // ! Menggunakan Loop Run (Data tidak muncul)
        // Loop::run(function () use ($connection) {
        //     $deferred = new Deferred();
        //     $promise_data = $connection->query('SELECT * FROM article_category')
        //         ->then(function (QueryResult $command) use (&$data, &$dataField, $deferred) {
        //             $data = $command->resultRows;
        //             $dataField = $command->resultFields;
        //             Log::info('data di database sudah diambil secara async');
        //             $deferred->resolve([$data, $dataField]);
        //         }, function ($err) use ($deferred) {
        //             return response()->json([
        //                 'message' => 'terjadi kesalahan dalam mengambil data di database',
        //                 'error' => $err
        //             ], 500);
        //         });

        //     // Mendapatkan hasil promise data dan printing
        //     $promise_data->then(function ($finalData) use ($deferred) {
        //         $deferred->promise()->then(function () use ($finalData){
        //             return response()->json([
        //                 'message' => 'data berhasil diambil',
        //                 'data' => $finalData
        //             ]);
        //         });
        //     }, function ($err) {
        //         return response()->json($err,500);
        //     });
        // });
    }

    // public function handlePromiseGetAllCategory(PromiseInterface $promise)
    // {
    //     Loop::run(function () use ($promise) {
    //         $promise->then(
    //             function ($finalData) {
    //                 return response()->json([
    //                     'message' => 'data berhasil diambil',
    //                     'data' => $finalData
    //                 ]);
    //             },
    //             function (Throwable $err) {
    //                 return response()->json([
    //                     'message' => 'terjadi kesalahan dalam mengambil data di database',
    //                     'error' => $err->getMessage()
    //                 ]);
    //             }
    //         );
    //     });
    // }

    protected function ambilDataDariDatabase($query)
    {
        $connection = $this->connector;
        return $connection->query($query)
            ->then(function (QueryResult $command) {
                $data = $command->resultRows;
                return $data;
            }, function ($err) {
                throw $err;
            });
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
