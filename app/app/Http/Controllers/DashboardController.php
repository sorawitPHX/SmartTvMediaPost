<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SmartTv;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('manage.index', [
            'totalUsers' => User::count(),
            'deletedUsers' => User::onlyTrashed()->count(),

            // ✅ Count เฉพาะ posts ที่ smartTv ยังไม่ถูกลบ
            'totalPosts' => Post::whereHas('smartTv', fn($q) => $q->whereNull('deleted_at'))->count(),
            'publicPosts' => Post::where('is_public', true)
                ->whereHas('smartTv', fn($q) => $q->whereNull('deleted_at'))
                ->count(),
            'deletedPosts' => Post::onlyTrashed()
                ->whereHas('smartTv', fn($q) => $q->whereNull('deleted_at'))
                ->count(),

            'latestPosts' => Post::whereHas('smartTv', fn($q) => $q->whereNull('deleted_at'))
                ->latest()
                ->with('smartTv')
                ->take(10)
                ->get(),
            'latestUsers' => User::latest()->take(5)->get(),
            'totalSmartTv' => SmartTv::count()
        ]);
    }
}
