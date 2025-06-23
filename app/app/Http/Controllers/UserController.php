<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::withTrashed()->get();
        return view('manage.users', compact('users'));
    }

    public function show($id)
    {
        return User::withTrashed()->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:staff,admin',
            'password' => 'required|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return back()->with('success', 'สร้างผู้ใช้สำเร็จ');
    }

    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $id,
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:staff,admin',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'แก้ไขข้อมูลผู้ใช้เรียบร้อย');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'ลบผู้ใช้ (ชั่วคราว) แล้ว');
    }

    public function restore($id)
    {
        User::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'กู้คืนผู้ใช้สำเร็จ');
    }

    public function bulkForceDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        User::withTrashed()->whereIn('id', $request->ids)->forceDelete();

        return response()->json(['success' => true]);
    }
}
