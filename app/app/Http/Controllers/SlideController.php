<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

use function Pest\Laravel\json;

class SlideController extends Controller
{
    //
    public function index()
    {
        $posts = Post::where('is_public', operator: true)
            ->orderBy('order')
            ->get();

        return view('index', compact('posts'));
    }

    public function getPost() {
        $posts = Post::where('is_public', operator: true)
            ->orderBy('order')
            ->get();
        return response()->json($posts);
    }
}
