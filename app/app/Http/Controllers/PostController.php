<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SmartTv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $translation = [
            'image' => '🖼️ รูปภาพ',
            'video' => '🎥 วิดิโอ'
        ];
        $posts = Post::orderBy('order')->get();
        $deleted_posts = Post::onlyTrashed()->get();
        return view(
            'manage.post',
            compact('posts', 'deleted_posts', 'translation')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**  POST /manage/post  – บันทึกโพสต์ใหม่ */
    public function store(Request $request)
    {
        $data = $request->all();
        $path = $request->file('file')->store('posts', 'public');


        $result = Post::create([
            'user_id'   => Auth::id(),
            'caption'   => $data['caption'],
            'is_mute'   => isset($data['is_mute']),
            'filename'  => $path,
            'type'      => $data['type'],
            'is_public' => isset($data['is_public']),
            'duration'  => $data['duration'] ?? -1,
            'order'     => $data['order'] ?? 0,
            'smart_tv_id'     => $data['smart_tv_id'],
        ]);

        return back()->with('success', 'สร้างโพสต์เรียบร้อย');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) return response()->json(["success" => false], 404);
        return response()->json(["success" => true, "post" => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**  PUT /manage/post/{post}  – อัปเดตโพสต์ */
    public function update(Request $request, Post $post)
    {
        $data = $request->all();
        $data['duration'] = isset($data['duration']) ? $data['duration'] : -1;
        $data['is_public'] = isset($data['is_public']);
        $data['is_mute'] = isset($data['is_mute']);
        if ($request->hasFile('file')) {
            // ลบไฟล์เก่าแล้วอัปโหลดใหม่
            Storage::disk('public')->delete($post->filename);
            $data['filename'] = $request->file('file')->store('posts', 'public');
            $data['type']     = str_starts_with($request->file('file')->getMimeType(), 'video') ? 'video' : 'image';
        }
        $result = $post->update($data);
        return back()->with('success', 'แก้ไขโพสต์แล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**  DELETE /manage/post/{post} – Soft‑delete */
    public function destroy(Post $post)
    {
        //
        $post->delete();
        return back()->with('success', 'ลบโพสต์สำเร็จ (สามารถกู้คืนได้)');
    }

    /**  POST /manage/post/{id}/restore – กู้คืนโพสต์ที่ลบแล้ว */
    public function restore($id)
    {
        Post::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'กู้คืนโพสต์เรียบร้อย');
    }

    /**  POST /api/posts/reorder  (AJAX จากหน้า Manage) */
    public function reorder(Request $request)
    {
        $request->validate([
            'updates'               => ['required', 'array'],
            'updates.*.id'          => ['required', 'integer', 'exists:posts,id'],
            'updates.*.order'       => ['required', 'integer', 'min:0'],
            'updates.*.is_public'   => ['required', 'boolean'],
        ]);

        foreach ($request->updates as $u) {
            Post::where('id', $u['id'])->update([
                'order'     => $u['order'],
                'is_public' => $u['is_public'],
            ]);
        }

        return response()->json(['success' => true], 200);
    }

    /*  -----------  กู้คืนหลายรายการ  ----------- */
    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);          //  array ของ ID
        Post::withTrashed()->whereIn('id', $ids)->restore();

        return response()->json([
            'success' => true,
            'message' => 'กู้คืนเรียบร้อย'
        ]);

        // return back()->with('success', 'กู้คืนเรียบร้อย');
    }

    /*  -----------  ลบถาวรหลายรายการ  ----------- */
    public function bulkForceDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        $posts = Post::withTrashed()->whereIn('id', $ids)->get();

        foreach ($posts as $post) {
            // ตรวจสอบว่าไฟล์นั้นเป็นไฟล์จริงๆ และไม่ใช่ null หรือ string ว่างเปล่า
            if ($post->filename && Storage::disk('public')->exists($post->filename)) {
                // ใช้ Storage Facade เพื่อลบไฟล์
                Storage::disk('public')->delete($post->filename);
            }
        }

        // ลบออกจากฐานข้อมูลแบบถาวร
        Post::withTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'ลบถาวรแล้วพร้อมไฟล์ใน storage'
        ]);
        // return back()->with('success', 'ลบถาวรแล้วพร้อมไฟล์ใน storage');
    }

    public function showBySmartTv(SmartTv $smartTv)
    {
        $posts = Post::where('is_public', true)
            ->where('smart_tv_id', $smartTv->id)
            ->orderBy('order')
            ->get();
        return response()->json($posts);
    }
}
