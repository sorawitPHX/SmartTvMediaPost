<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\SmartTv;

use function Pest\Laravel\json;

class SlideController extends Controller
{
    //
    public function index(Request $request)
    {
        $smart_tv_id = $request->query('id');
        if ($smart_tv_id) {
            $smartTv = SmartTv::where('is_public', true)
                ->where('id', $smart_tv_id)
                ->firstOrFail();
            if (!$smartTv) return abort(404);
            $posts = Post::where('is_public', operator: true)
                ->where('smart_tv_id', $smartTv->id)
                ->orderBy('order')
                ->get();
            return view('slides.index', compact('posts', 'smartTv'));
        } else {
            $smartTvs = SmartTv::where('is_public', true)
                ->get();
            return view('slides.select_tv', compact('smartTvs'));
        }
    }

    public function show(SmartTv $smartTv)
    {
        $posts = Post::where('is_public', operator: true)
            ->where('smart_tv_id', $smartTv->id)
            ->orderBy('order')
            ->get();
        return view('slides.index', compact('posts', 'smartTv'));
    }
}
