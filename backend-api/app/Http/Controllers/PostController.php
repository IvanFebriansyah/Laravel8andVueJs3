<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    //
    public function index()
    {
        $posts = Post::latest()->get();

        return response()->json(
            [
                'succes' => true,
                'message' => 'List Data Post',
                'data' => $posts
            ],
            200

        );
    }

    public function show($id)
    {
        // Menemukan id post
        $post = Post::findOrfail($id);

        // Membuat respons JSON
        return response()->json([
            'succes' => true,
            'message' => 'Detail Data Post',
            'data' => $post
        ], 200);
    }

    public function store(Request $request)
    {

        // set validation
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        // response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // save to database
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        // Succes save to database
        if ($post) {
            return response()->json([
                'succes' => true,
                'message' => 'Post Created',
                'data' => $post
            ], 200);
        }

        // failed to save database
        return response()->json([
            'succes' => false,
            'message' => 'Post Failed to Save'
        ], 409);
    }

    public function update(Request $request, Post $post)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        // responses error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // find post by ID
        $post = Post::findOrFail($post->id);

        if ($post) {

            // Update post
            $post->update([
                'title' => $request->title,
                'content' => $request->content
            ]);

            return response()->json([
                'succes' => true,
                'message' => 'Post Updated',
                'data' => $post
            ], 200);
        }
    }


    public function destroy($id)
    {
        // find post by id
        $post = Post::findOrfail($id);

        if ($post) {
            // delete post
            $post->delete();


            return response()->json([
                'succes' => true,
                'message' => 'Post Deleted',
            ], 200);
        }

        // data post not found

        return response()->json([
            'succes' => false,
            'message' => 'Post Not Found'
        ], 404);
    }
}