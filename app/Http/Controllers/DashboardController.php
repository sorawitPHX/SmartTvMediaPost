<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        return view('manage.index', [
            'totalUsers' => User::count(),
            'deletedUsers' => User::onlyTrashed()->count(),
            'totalPosts' => Post::count(),
            'publicPosts' => Post::where('is_public', true)->count(),
            'deletedPosts' => Post::onlyTrashed()->count(),
            'latestPosts' => Post::latest()->take(5)->get(),
            'latestUsers' => User::latest()->take(5)->get(),
        ]);
    }
}
