<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
        return view('manage.post', compact('posts', 'deleted_posts', 'translation'));
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
        /* upload file → storage/app/public/posts/… */
        $path = $request->file('file')->store('posts', 'public');

        // (option) ตรวจความยาววิดีโอหากเป็น video และ duration ≠ -1
        // if ($data['type'] === 'video' && $data['duration'] !== -1) {
        //     // NOTE: ถ้าต้องแม่นยำให้ใช้ FFprobe; ที่นี่สมมติว่า dev ติดตั้งไว้และ helper exist
        //     $videoSeconds = get_video_duration(storage_path("app/public/{$path}")); // helper ของคุณ
        //     abort_if($data['duration'] > $videoSeconds, 422, 'Duration เกินความยาววิดีโอ');
        // }

        $result = Post::create([
            'user_id'   => Auth::id(),
            'caption'   => $data['caption'],
            'is_mute'   => isset($data['is_mute']),
            'filename'  => $path,
            'type'      => $data['type'],
            'is_public' => isset($data['is_public']),
            'duration'  => $data['duration'] ?? -1,
            'order'     => $data['order'] ?? 0,
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
        return back()->with('success', 'ลบโพสต์ (กู้คืนได้) สำเร็จ');
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
}
