<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SmartTv;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmartTvController extends Controller
{
    // 📺 แสดง smartTV ทั้งหมด
    public function index()
    {
        $smartTvs = SmartTv::withCount('posts')
        ->orderByDesc('created_at')
        ->get();
        $deletedSmartTvs = SmartTv::onlyTrashed()->get();
        return view(
            'manage.smarttvs',
            compact(
                'smartTvs',
                'deletedSmartTvs'
            )
        );
    }

    // ✅ สร้าง SmartTV ใหม่
    public function store(Request $request)
    {
        try {
            $result = SmartTv::create([
                'name' => $request->name,
                'is_public' => $request->is_public
            ]);

            return response()->json([
                'success' => true,
                'message' => 'สร้าง Smart TV สำเร็จแล้ว',
                'result' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ]);
        }
    }

    // ✏️ แก้ไข SmartTV
    public function update(Request $request, SmartTv $smarttv)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        // ]);

        try {
            $result = $smarttv->update([
                'name' => $request->name,
                'is_public' => $request->is_public ? true : false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดต Smart TV สำเร็จแล้ว',
                'result' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e,
            ], 500);
        }
    }

    // 🗑 ลบ SmartTV
    public function destroy(SmartTv $smarttv)
    {
        $smarttv->delete();

        return response()->json([
            'success' => true,
            'message' => 'ลบ Smart TV สำเร็จแล้ว',
        ]);
    }

    // 📂 เมื่อเลือก TV แล้ว redirect ไป reuse หน้า manage post เดิม
    public function show(SmartTv $smarttv)
    {
        // แปลชนิดโพสต์
        $translation = [
            'image' => '🖼️ รูปภาพ',
            'video' => '🎥 วิดีโอ'
        ];

        // ดึงโพสต์ทั้งหมดของ SmartTV นี้ (ที่ยังไม่ถูกลบ)
        $posts = Post::where('smart_tv_id', $smarttv->id)
            ->orderBy('order')
            ->get();

        // ดึงโพสต์ที่ถูกลบไปแล้ว (ถังขยะ)
        $deleted_posts = Post::where('smart_tv_id', $smarttv->id)
            ->onlyTrashed()
            ->get();

        // ส่งข้อมูลทั้งหมดไปยัง view
        return view('manage.posts', compact(
            'translation',
            'posts',
            'deleted_posts',
            'smarttv'
        ));
    }

    public function forceDelete(Request $request): JsonResponse
    {
        // $request->validate([
        //     'ids' => 'required|array',
        //     'ids.*' => 'exists:smart_tvs,id',
        // ]);

        DB::beginTransaction();
        try {
            SmartTv::onlyTrashed()->whereIn('id', $request->ids)->forceDelete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'ลบ Smart TV ถาวรสำเร็จแล้ว',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SmartTV force delete failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการลบถาวร',
            ]);
        }
    }

    public function restore(Request $request): JsonResponse
    {
        // $request->validate([
        //     'ids' => 'required|array',
        //     'ids.*' => 'exists:smart_tvs,id',
        // ]);

        try {
            SmartTv::onlyTrashed()->whereIn('id', $request->ids)->restore();

            return response()->json([
                'success' => true,
                'message' => 'กู้คืน Smart TV สำเร็จแล้ว',
            ]);
        } catch (\Exception $e) {
            Log::error('SmartTV restore failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการกู้คืน',
            ]);
        }
    }
}
