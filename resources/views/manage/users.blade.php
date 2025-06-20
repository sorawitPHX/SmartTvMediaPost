@section('title')
    จัดการผู้ใช้
@endsection

<x-app-layout>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Header Section with improved spacing and visual hierarchy -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 ">จัดการผู้ใช้</h1>
                    <p class="text-gray-600 mt-1">จัดการบัญชีผู้ใช้และสิทธิ์การเข้าถึงระบบ</p>
                </div>
                <button class="btn btn-primary gap-2 shadow-lg hover:shadow-xl transition-all duration-200"
                    onclick="openCreateUserModal()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    เพิ่มผู้ใช้ใหม่
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats shadow-lg bg-gradient-to-r from-blue-50 to-indigo-50 ">
                <div class="stat">
                    <div class="stat-figure text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="stat-title text-blue-800 ">ผู้ใช้ทั้งหมด</div>
                    <div class="stat-value text-blue-900 ">{{ $users->count() }}</div>
                </div>
            </div>

            <div class="stats shadow-lg bg-gradient-to-r from-green-50 to-emerald-50 ">
                <div class="stat">
                    <div class="stat-figure text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-title text-green-800 ">ผู้ใช้ที่ใช้งานอยู่</div>
                    <div class="stat-value text-green-900 ">
                        {{ $users->where('deleted_at', null)->count() }}</div>
                </div>
            </div>

            <div class="stats shadow-lg bg-gradient-to-r from-orange-50 to-red-50 ">
                <div class="stat">
                    <div class="stat-figure text-orange-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div class="stat-title text-orange-800 ">ผู้ใช้ที่ถูกลบ</div>
                    <div class="stat-value text-orange-900 ">
                        {{ $users->whereNotNull('deleted_at')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card bg-base-100 shadow-lg mb-6">
            <div class="card-body p-4">
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div class="form-control flex-1 max-w-md">
                        <div class="input-group flex">
                            <input type="text" id="searchInput" placeholder="ค้นหาผู้ใช้..."
                                class="input input-bordered flex-1" onkeyup="filterUsers()">
                            <button class="btn btn-square btn-ghost">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <div class="form-control">
                            <select id="roleFilter" class="select select-bordered" onchange="filterUsers()">
                                <option value="">ทุกสิทธิ์</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <select id="statusFilter" class="select select-bordered" onchange="filterUsers()">
                                <option value="">ทุกสถานะ</option>
                                <option value="active">ใช้งานอยู่</option>
                                <option value="deleted">ถูกลบ</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Users Table -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200">
                            <tr>
                                <th class="text-left font-semibold">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        ข้อมูลผู้ใช้
                                    </div>
                                </th>
                                <th class="text-center font-semibold">สิทธิ์</th>
                                <th class="text-center font-semibold">สถานะ</th>
                                <th class="text-center font-semibold">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            @foreach ($users as $user)
                                <tr class="hover:bg-base-50 transition-colors duration-150 {{ $user->trashed() ? 'opacity-60 bg-red-50 ' : '' }}"
                                    data-user-name="{{ strtolower($user->name) }}"
                                    data-user-email="{{ strtolower($user->email) }}"
                                    data-user-username="{{ strtolower($user->username) }}"
                                    data-user-role="{{ $user->role }}"
                                    data-user-status="{{ $user->trashed() ? 'deleted' : 'active' }}">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar avatar-placeholder">
                                                <div
                                                    class="bg-gradient-to-br from-primary to-secondary text-primary-content rounded-full w-12 h-12">
                                                    <span
                                                        class="text-lg font-bold">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-base-content">{{ $user->name }}</div>
                                                <div class="text-sm text-base-content/70">{{ $user->username }}</div>
                                                <div class="text-xs text-base-content/60">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($user->role === 'admin')
                                            <div class="badge badge-error gap-1 font-medium">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                Admin
                                            </div>
                                        @else
                                            <div class="badge badge-primary gap-1 font-medium">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                Staff
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->trashed())
                                            <div class="badge badge-ghost gap-1 text-error border-error">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />
                                                </svg>
                                                ถูกลบ
                                            </div>
                                        @else
                                            <div class="badge badge-success gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                ใช้งานอยู่
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            @if ($user->trashed())
                                                <form method="POST"
                                                    action="{{ route('manage.users.restore', $user->id) }}"
                                                    class="inline">
                                                    @csrf
                                                    <button type="button"
                                                        class="btn btn-sm btn-success gap-1 hover:scale-105 transition-transform"
                                                        onclick="confirmRestore(this.form)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                        กู้คืน
                                                    </button>
                                                </form>
                                            @else
                                                <!-- ป้องกันไม่ให้ user แก้ไข/ลบตัวเอง -->
                                                @if (auth()->id() !== $user->id)
                                                    <button
                                                        class="btn btn-sm btn-warning gap-1 hover:scale-105 transition-transform"
                                                        onclick="openEditUserModal({{ $user->id }})">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        แก้ไข
                                                    </button>
                                                    <button
                                                        class="btn btn-sm btn-error gap-1 hover:scale-105 transition-transform"
                                                        onclick="openDeleteUserModal({{ $user->id }}, '{{ $user->name }}')">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        ลบ
                                                    </button>
                                                @else
                                                    <div class="tooltip"
                                                        data-tip="คุณไม่สามารถแก้ไขบัญชีของตัวเองได้">
                                                        <button class="btn btn-sm btn-disabled gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                            </svg>
                                                            บัญชีของคุณ
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Empty state -->
                <div id="emptyState" class="text-center py-12 hidden">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">ไม่พบผู้ใช้</h3>
                    <p class="text-gray-500 ">ลองปรับเปลี่ยนเงื่อนไขการค้นหาหรือตัวกรอง</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Create User Modal -->
    <dialog id="createUserModal" class="modal">
        <div class="modal-box max-w-md">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-primary/10 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-xl">เพิ่มผู้ใช้ใหม่</h3>
                    <p class="text-sm text-base-content/70">กรอกข้อมูลผู้ใช้ใหม่</p>
                </div>
            </div>

            <form id="createUserForm" method="POST" action="{{ route('manage.users.store') }}">
                @csrf
                <div class="">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">ชื่อ-นามสกุล <span class="text-error">*</span></legend>
                        <input name="name" type="text" class="input input-bordered focus:input-primary w-full"
                            placeholder="กรอกชื่อ-นามสกุล" required>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Username <span class="text-error">*</span></legend>
                        <input name="username" type="text" class="input input-bordered focus:input-primary w-full"
                            placeholder="กรอก username" required>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Email <span class="text-error">*</span></legend>
                        <input name="email" type="email" class="input input-bordered focus:input-primary w-full"
                            placeholder="กรอก email" required>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">สิทธิ์การใช้งาน <span class="text-error">*</span></legend>
                        <select name="role" class="select select-bordered focus:select-primary w-full" required>
                            <option value="" disabled selected>เลือกสิทธิ์</option>
                            <option value="staff">Staff - พนักงานทั่วไป</option>
                            <option value="admin">Admin - ผู้ดูแลระบบ</option>
                        </select>
                    </fieldset>

                    <div class="grid grid-cols-2 gap-4">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">รหัสผ่าน <span class="text-error">*</span></legend>
                            <input name="password" type="password"
                                class="input input-bordered focus:input-primary w-full" placeholder="กรอกรหัสผ่าน"
                                required minlength="8">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">ยืนยันรหัสผ่าน <span class="text-error">*</span></legend>
                            <input name="password_confirmation" type="password"
                                class="input input-bordered focus:input-primary w-full" placeholder="ยืนยันรหัสผ่าน"
                                required minlength="8">
                        </fieldset>
                    </div>
                </div>

                <div class="modal-action mt-8">
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        บันทึกข้อมูล
                    </button>
                    <button type="button" onclick="createUserModal.close()" class="btn btn-ghost">ยกเลิก</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Enhanced Edit User Modal -->
    <dialog id="editUserModal" class="modal">
        <div class="modal-box max-w-md">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-warning/10 rounded-lg">
                    <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-xl">แก้ไขข้อมูลผู้ใช้</h3>
                    <p class="text-sm text-base-content/70">แก้ไขข้อมูลผู้ใช้ในระบบ</p>
                </div>
            </div>

            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">ชื่อ-นามสกุล <span class="text-error">*</span></legend>
                        <input name="name" type="text" id="editName" class="input input-bordered focus:input-primary w-full"
                            placeholder="กรอกชื่อ-นามสกุล" required>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Username <span class="text-error">*</span></legend>
                        <input name="username" type="text" id="editUsername" class="input input-bordered focus:input-primary w-full"
                            placeholder="กรอก username" required>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Email <span class="text-error">*</span></legend>
                        <input name="email" type="email" id="editEmail" class="input input-bordered focus:input-primary w-full"
                            placeholder="กรอก email" required>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">สิทธิ์การใช้งาน <span class="text-error">*</span></legend>
                        <select name="role" id="editRole" class="select select-bordered focus:select-primary w-full" required>
                            <option value="" disabled selected>เลือกสิทธิ์</option>
                            <option value="staff">Staff - พนักงานทั่วไป</option>
                            <option value="admin">Admin - ผู้ดูแลระบบ</option>
                        </select>
                    </fieldset>

                    <div class="grid grid-cols-2 gap-4">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">รหัสผ่าน <span class="text-error"></span></legend>
                            <input name="password" type="password"
                                class="input input-bordered focus:input-primary w-full" placeholder="กรอกรหัสผ่าน"
                                 minlength="8">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">ยืนยันรหัสผ่าน <span class="text-error"></span></legend>
                            <input name="password_confirmation" type="password"
                                class="input input-bordered focus:input-primary w-full" placeholder="ยืนยันรหัสผ่าน"
                                 minlength="8">
                        </fieldset>
                    </div>
                </div>

                <div class="modal-action mt-8">
                    <button type="submit" class="btn btn-warning gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        อัปเดตข้อมูล
                    </button>
                    <button type="button" onclick="editUserModal.close()" class="btn btn-ghost">ยกเลิก</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Enhanced Delete Confirm Modal -->
    <dialog id="deleteUserModal" class="modal">
        <div class="modal-box max-w-md">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-error/10 rounded-lg">
                    <svg class="w-6 h-6 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.317-1.859 1.33-2.797L13.243 4.715c-.615-.885-2.87-.885-3.485 0L2.758 17.203C1.771 18.141 2.548 20 4.088 20z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-error">ยืนยันการลบผู้ใช้</h3>
                    <p class="text-sm text-base-content/70">การดำเนินการนี้ไม่สามารถยกเลิกได้</p>
                </div>
            </div>

            <div class="bg-error/5 border border-error/20 rounded-lg p-4 mb-6">
                <p class="text-sm">
                    คุณกำลังจะลบผู้ใช้ <span id="deleteUserName" class="font-bold text-error"></span>
                    ออกจากระบบ ข้อมูลของผู้ใช้จะถูกย้ายไปที่ถังขยะและสามารถกู้คืนได้ในภายหลัง
                </p>
            </div>

            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-action">
                    <button type="submit" class="btn btn-error gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        ยืนยันลบผู้ใช้
                    </button>
                    <button type="button" onclick="deleteUserModal.close()" class="btn btn-ghost">ยกเลิก</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        // Enhanced user management functions with better UX
        function openCreateUserModal() {
            document.getElementById('createUserForm').reset();
            createUserModal.showModal();
        }

        function openEditUserModal(id) {
            // Show loading state
            const modal = document.getElementById('editUserModal');
            const form = document.getElementById('editUserForm');

            // Reset form
            form.reset();

            fetch(`{{ url('/manage/users') }}/${id}`)
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch user data');
                    return res.json();
                })
                .then(data => {
                    form.action = `{{ url('/manage/users') }}/${id}`;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editUsername').value = data.username;
                    document.getElementById('editEmail').value = data.email;
                    document.getElementById('editRole').value = data.role;
                    modal.showModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถโหลดข้อมูลผู้ใช้ได้',
                        confirmButtonText: 'ตกลง'
                    });
                });
        }

        function openDeleteUserModal(id, userName) {
            document.getElementById('deleteUserForm').action = `{{ url('/manage/users') }}/${id}`;
            document.getElementById('deleteUserName').textContent = userName;
            deleteUserModal.showModal();
        }

        function confirmRestore(form) {
            Swal.fire({
                title: 'ยืนยันการกู้คืน?',
                text: 'คุณต้องการกู้คืนผู้ใช้นี้หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ยืนยันกู้คืน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Enhanced search and filter functionality
        function filterUsers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const roleFilter = document.getElementById('roleFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            const rows = document.querySelectorAll('#usersTableBody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.dataset.userName || '';
                const email = row.dataset.userEmail || '';
                const username = row.dataset.userUsername || '';
                const role = row.dataset.userRole || '';
                const status = row.dataset.userStatus || '';

                const matchesSearch = searchTerm === '' ||
                    name.includes(searchTerm) ||
                    email.includes(searchTerm) ||
                    username.includes(searchTerm);

                const matchesRole = roleFilter === '' || role === roleFilter;
                const matchesStatus = statusFilter === '' || status === statusFilter;

                if (matchesSearch && matchesRole && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide empty state
            const emptyState = document.getElementById('emptyState');
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        // Form validation and submission with SweetAlert2
        document.getElementById('createUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = this.querySelector('input[name="password"]').value;
            const passwordConfirm = this.querySelector('input[name="password_confirmation"]').value;

            if (password !== passwordConfirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณาตรวจสอบรหัสผ่านและยืนยันรหัสผ่านให้ตรงกัน',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            if (password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านสั้นเกินไป',
                    text: 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'กำลังเพิ่มผู้ใช้...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            this.submit();
        });

        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = this.querySelector('input[name="password"]').value;
            const passwordConfirm = this.querySelector('input[name="password_confirmation"]').value;

            if (password && password !== passwordConfirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณาตรวจสอบรหัสผ่านและยืนยันรหัสผ่านให้ตรงกัน',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            if (password && password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านสั้นเกินไป',
                    text: 'รหัسผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'กำลังอัปเดตข้อมูล...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            this.submit();
        });

        document.getElementById('deleteUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading
            Swal.fire({
                title: 'กำลังลบผู้ใช้...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            this.submit();
        });

        // Auto-close modals on successful submission (if using AJAX)
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling to top when page loads
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Enhanced keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K to focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    document.getElementById('searchInput').focus();
                }

                // Escape to close modals
                if (e.key === 'Escape') {
                    const modals = document.querySelectorAll('dialog[open]');
                    modals.forEach(modal => modal.close());
                }
            });

            // Add loading states to buttons
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                    }
                });
            });
        });

        // Add real-time search (debounced)
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterUsers, 300);
        });

        // Show success/error messages if they exist in session
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                confirmButtonText: 'ตกลง',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: '{{ session('error') }}',
                confirmButtonText: 'ตกลง'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'ข้อมูลไม่ถูกต้อง',
                html: '@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach',
                confirmButtonText: 'ตกลง'
            });
        @endif
    </script>
</x-app-layout>
