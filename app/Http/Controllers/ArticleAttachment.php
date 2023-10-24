<?php

namespace App\Http\Controllers;

use App\Models\Article;
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
        ],201);
    }

    public function create(Request $request, $articleId){
        $request['created_at'] = time();
        $article = Article::find($articleId);
        if (!$article){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        }
        $attachmentData = $request->all();
        $attachmentData['article_id'] = $articleId;

        $attachment = Article_attachment::create($attachmentData);
        return response()->json([
            'message' => 'data berhasil ditambahkan',
            'data' => $attachment
        ],201);
    }

    public function update(Request $request, $id){
        $attachment = Article_attachment::find($id);
        if (!$attachment){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $attachment->update($request->all());
        return response()->json([
            'message' => 'data berhasil diupdate',
            'data' => $attachment
        ],200);
    }

    public function hapus($id){
        $attachment = Article_attachment::find($id);
        if (!$attachment){
            return response()->json([
                'message' => 'data tidak ditemukan'
            ],404);
        }
        $attachment->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ],200);
    }
}
