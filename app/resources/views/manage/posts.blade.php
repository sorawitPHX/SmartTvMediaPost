@section('title')
    จัดการโพสต์
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex gap-3 items-center">
            <a href="{{ route('manage.smarttvs.index') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path
                        d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                </svg>
                <span>
                    ย้อนกลับ
                </span>
            </a>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('manage.smarttvs.index') }}">จัดการ Smart TV</a></li>
                    <li class="opacity-70">จัดการโพสต์</li>
                </ul>
            </div>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white min-h-[70vh] shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="flex gap-3 flex-col sm:flex-row items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 ">
                                <span class="">Smart TV</span>
                                <span class="badge badge-primary">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ $smarttv->name }}
                                </span>
                            </h1>
                            <p class="text-gray-600 mt-1">จัดการโพสต์และการแสดงผลของโพสต์</p>
                        </div>
                        <div>
                            <button class="btn btn-dash btn-secondary" onclick="openBinModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                </svg>
                                <span>ถังขยะ</span>
                                @if (count($deleted_posts) > 0)
                                    <span class="badge badge-sm badge-secondary">{{ count($deleted_posts) }}</span>
                                @endif
                            </button>
                            <button class="btn btn-primary" onclick="openCreateModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                </svg>
                                <span>
                                    เพิ่มโพสต์ใหม่
                                </span>
                            </button>
                        </div>
                    </h2>

                    <hr class="my-5">

                    @if (count($posts) == 0)
                        <div role="alert" class="alert alert-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="h-6 w-6 shrink-0 stroke-current">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>ไม่พบโพสต์ใดๆ กรุณาเพิ่มโพสต์ใหม่</span>
                        </div>
                    @else
                        <!-- Tabs: แสดงผล / แบบซ่อน -->
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- แสดงผลจริง (is_public = true) -->
                            <div class="w-full lg:w-1/2">
                                <h3 class="text-lg font-semibold mb-2">โพสต์สาธารณะ</h3>
                                <ul id="public-posts" class="space-y-3 min-h-[400px] border rounded p-3 bg-slate-50">
                                    @foreach ($posts->where('is_public', true)->sortBy('order') as $post)
                                        <li class="post-item bg-white rounded shadow p-3 cursor-move"
                                            data-id="{{ $post->id }}">
                                            <div class="flex items-center gap-4">
                                                <div class="w-20 h-20 overflow-hidden rounded">
                                                    @if ($post->type === 'image')
                                                        <img src="{{ asset('storage/' . $post->filename) }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <video src="{{ asset('storage/' . $post->filename) }}"
                                                            class="w-full h-full object-cover" muted></video>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-semibold">
                                                        {{ $post->caption ?: '(ไม่มีคำอธิบาย)' }}
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        ระยะเวลาแสดงผล:
                                                        {{ $post->duration == -1 ? 'เล่นจนจบ' : $post->duration . ' วินาที' }}
                                                    </div>
                                                    <div class="text-sm text-gray-600">ประเภท:
                                                        {{ $translation[$post->type] }}</div>
                                                    @if ($post->type == 'video')
                                                        <div class="text-sm text-gray-600">เสียง:
                                                            {{ $post->is_mute ? '🔇' : '🔊' }}</div>
                                                    @endif
                                                </div>
                                                <div class="ms-auto mb-auto dropdown">
                                                    <div tabindex="0" role="button" class="">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                            <path
                                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                                        </svg>
                                                    </div>
                                                    <ul tabindex="0"
                                                        class="dropdown-content menu bg-base-300 relative flex flex-col gap-1 rounded-box z-auto w-28 p-2 shadow-sm">
                                                        <li><a class="btn btn-sm btn-warning"
                                                                onclick="openEditModal({{ $post->id }})">แก้ไข</a>
                                                        </li>
                                                        <li><a class="btn btn-sm btn-error"
                                                                onclick="openDeleteModal({{ $post->id }})">ลบ</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- แบบซ่อน (is_public = false) -->
                            <div class="w-full lg:w-1/2 ">
                                <h3 class="text-lg font-semibold mb-2">โพสต์ที่ซ่อนไว้ / ฉบับร่าง</h3>
                                <ul id="hidden-posts" class="space-y-3 min-h-[400px] bg-slate-50 border rounded p-3">
                                    @foreach ($posts->where('is_public', false)->sortBy('order') as $post)
                                        <li class="post-item bg-white rounded shadow p-3 cursor-move"
                                            data-id="{{ $post->id }}">
                                            <div class="flex items-center gap-4">
                                                <div class="w-20 h-20 overflow-hidden rounded">
                                                    @if ($post->type === 'image')
                                                        <img src="{{ asset('storage/' . $post->filename) }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <video src="{{ asset('storage/' . $post->filename) }}"
                                                            class="w-full h-full object-cover" muted></video>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-semibold">
                                                        {{ $post->caption ?: '(ไม่มีคำอธิบาย)' }}
                                                    </div>
                                                    <div class="text-sm text-gray-600">ระยะเวลาแสดงผล:
                                                        {{ $post->duration == -1 ? 'เล่นจนจบ' : $post->duration . ' วินาที' }}
                                                    </div>
                                                    <div class="text-sm text-gray-600">ประเภท:
                                                        {{ $translation[$post->type] }}</div>
                                                    @if ($post->type == 'video')
                                                        <div class="text-sm text-gray-600">เสียง:
                                                            {{ $post->is_mute ? '🔇' : '🔊' }}</div>
                                                    @endif
                                                </div>
                                                <div class="ms-auto mb-auto dropdown">
                                                    <div tabindex="0" role="button" class="">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                            <path
                                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                                        </svg>
                                                    </div>
                                                    <ul tabindex="0"
                                                        class="dropdown-content menu bg-base-300 relative flex flex-col gap-1 rounded-box z-auto w-28 p-2 shadow-sm">
                                                        <li><a class="btn btn-sm btn-warning"
                                                                onclick="openEditModal({{ $post->id }})">แก้ไข</a>
                                                        </li>
                                                        <li><a class="btn btn-sm btn-error"
                                                                onclick="openDeleteModal({{ $post->id }})">ลบ</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- ปุ่ม Save Change -->
                        <div class="mt-6 text-right">
                            <button id="save-order" class="btn btn-success hidden">บันทึกลำดับ /
                                การเปลี่ยนแปลง</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <dialog id="createModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-2">เพิ่มโพสต์ใหม่</h3>
            <form id="createForm" method="POST" action="{{ route('manage.post.store') }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="smart_tv_id" value="{{ $smarttv->id }}">

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">ไฟล์ ภาพ/วิดิโอ (แนะนำขนาด < 5GB เป็นอัตราส่วน 16:9)</legend>
                            <input id="createFile" required
                                onchange="showPreview(this, 'createPreviewFile'); findFileType(this, 'craeteFileType');"
                                type="file" name="file" accept="image/*,video/*"
                                class="input-neutral file-input file-input-bordered w-full">
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">คำอธิบายภาพ</legend>
                    <input name="caption" type="text" class="input input-neutral w-full"
                        placeholder="คำอธิบาย" />
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">ประเภท</legend>
                    <input type="text" id="craeteFileType" required name="type"
                        placeholder="กรุณาอัพโหลดไฟล์ก่อน" class="input disabled w-full" readonly>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">ระยะเวลาแสดงผล (วินาที)</legend>
                    <input type="number" value="5" name="duration" min="1" step="1"
                        class="input input-neutral w-full mb-2" placeholder="ระยะเวลา (วินาที)">
                </fieldset>


                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" checked name="is_public" class="checkbox"> แสดงต่อสาธารณะ
                </label>
                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" disabled name="is_mute" class="checkbox"> ปิดเสียงวิดีโอ
                </label>
                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" disabled name="full_watch" class="checkbox"> เล่นจนจบวิดิโอ
                </label>

                <div id="createPreviewFile" class="flex items-center justify-center"></div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                    <button type="button" onclick="createModal.close()" class="btn">ยกเลิก</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Edit Modal -->
    <dialog id="editModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-2">แก้ไขโพสต์</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">ประเภท</legend>
                    <input type="text" id="editFileType" required name="type"
                        placeholder="กรุณาอัพโหลดไฟล์ก่อน" class="input disabled w-full" readonly>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">เปลี่ยนแปลงไฟล์ใหม่</legend>
                    <input id="editFile"
                        onchange="showPreview(this, 'editPreviewFile'); findFileType(this, 'craeteFileType');"
                        type="file" name="file" accept="image/*,video/*"
                        class="input-neutral file-input file-input-bordered w-full">
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">คำอธิบาย</legend>
                    <input type="text" name="caption" id="editCaption"
                        class="input input-neutral input-bordered w-full">
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">ระยะเวลาแสดงผล (วินาที)</legend>
                    <input type="number" min="1" required step="1" name="duration" id="editDuration"
                        class="input input-bordered w-full mb-2">
                </fieldset>
                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" name="is_public" id="editPublic" class="checkbox"> แสดงต่อสาธารณะ
                </label>
                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" name="is_mute" id="editMute" class="checkbox"> ปิดเสียง
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="full_watch" id="editFullWatch" class="checkbox"> เล่นจนจบวิดิโอ
                </label>

                <div id="editPreviewFile" class="flex items-center justify-center"></div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">อัปเดต</button>
                    <button type="button" onclick="editModal.close()" class="btn">ยกเลิก</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Confirm Modal -->
    <dialog id="deleteModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">ยืนยันการลบ</h3>
            <p class="py-4">คุณแน่ใจหรือไม่ว่าต้องการลบโพสต์นี้?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('delete')
                <div class="modal-action">
                    <button type="submit" class="btn btn-error">ยืนยันลบ</button>
                    <button type="button" onclick="deleteModal.close()" class="btn">ยกเลิก</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Bin Modal -->
    <dialog id="binModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box w-11/12 max-w-3xl z-[999]">
            <h3 class="font-bold text-lg mb-4">ถังขยะ</h3>

            <form id="binForm" class="space-y-3 max-h-80 bg-slate-50 p-3 shadow rounded-md overflow-y-auto">
                @if (count($deleted_posts) == 0)
                    <div role="alert" class="alert alert-info alert-outline">
                        ไม่พบรายการใดๆ
                    </div>
                @endif
                @foreach ($deleted_posts as $post)
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="checkbox item-check" value="{{ $post->id }}">
                        <div class="w-16 h-16 overflow-hidden rounded">
                            @if ($post->type === 'image')
                                <img src="{{ asset('storage/' . $post->filename) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <video src="{{ asset('storage/' . $post->filename) }}"
                                    class="w-full h-full object-cover" muted></video>
                            @endif
                        </div>
                        <span class="truncate flex-1">
                            {{ $post->caption ?: '(ไม่มีคำอธิบาย)' }}
                        </span>
                    </label>
                @endforeach
            </form>

            <div class="modal-action">
                <button type="button" class="btn" onclick="binModal.close()">ยกเลิก</button>
                <button type="button" class="btn btn-error" onclick="submitBin('delete')">ลบถาวร</button>
                <button type="button" class="btn btn-success"onclick="submitBin('restore')">กู้คืน</button>
            </div>
        </div>
    </dialog>

    <!-- Modal Confirm: ยืนยันการจัดการโพสต์ -->
    <input type="checkbox" id="confirm-bin-modal" class="modal-toggle" />
    <div class="modal z-[9999]" role="dialog">
        <div class="modal-box">
            <h3 class="font-bold text-lg">ยืนยันการดำเนินการ</h3>
            <p class="py-4" id="confirm-bin-message">คุณแน่ใจหรือไม่ว่าต้องการดำเนินการนี้?</p>
            <div class="modal-action">
                <label for="confirm-bin-modal" class="btn">ยกเลิก</label>
                <button class="btn btn-primary" onclick="submitConfirmedBin()">ยืนยัน</button>
            </div>
        </div>
    </div>



    <!-- Include SortableJS หรือ drag & drop lib อื่น -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script> --}}
    <script>
        const publicList = document.getElementById('public-posts');
        const hiddenList = document.getElementById('hidden-posts');
        const saveBtn = document.getElementById('save-order');

        document.addEventListener('DOMContentLoaded', () => {
            [publicList, hiddenList].forEach(list => {
                Sortable.create(list, {
                    group: 'posts',
                    animation: 150,
                    onEnd: () => {
                        saveBtn.classList.remove('hidden');
                    }
                });
            });
        })

        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                const publicIds = [...publicList.querySelectorAll('.post-item')].map((el, idx) => ({
                    id: el.dataset.id,
                    order: idx,
                    is_public: true
                }));
                const hiddenIds = [...hiddenList.querySelectorAll('.post-item')].map((el, idx) => ({
                    id: el.dataset.id,
                    order: idx,
                    is_public: false
                }));
                const combined = [...publicIds, ...hiddenIds];

                fetch("{{ route('manage.post.reorder') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            updates: combined
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            saveBtn.classList.add('hidden');
                            alertMessage('success', 'บันทึกเรียบร้อย');
                        }
                    });
            });
        }

        function openCreateModal() {
            createModal.showModal();
        }

        function openEditModal(id) {

            fetch(`{{ url('/manage/post/') }}/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data?.success) {
                        if (!editFullWatch.dataset.event) {
                            editFullWatch.addEventListener('change', () => {
                                editFullWatch.dataset.event = true
                                if (editFullWatch.checked) {
                                    editDuration.disabled = true
                                    editDuration.value = null
                                } else {
                                    editDuration.disabled = false
                                }
                            })
                        }

                        data = data.post
                        editForm.action = `{{ url('/manage/post') }}/${id}`;
                        editFileType.value = data.type || '';
                        editCaption.value = data.caption || '';
                        editDuration.value = data.duration == -1 ? null : data.duration;
                        editPublic.checked = data.is_public;
                        editMute.disabled = data.type == 'image' ? true : false
                        editMute.checked = data.is_mute;
                        editFullWatch.disabled = data.type == 'image' ? true : false
                        editFullWatch.checked = data.duration == -1 ? true : false
                        editDuration.disabled = data.duration == -1 ? true : false

                        editModal.showModal();
                    } else {
                        alertMessage('error', 'เกิดข้อผิดพลาด')

                    }
                }).catch(e => {
                    alertMessage('error', 'เกิดข้อผิดพลาด')
                })
        }

        function openDeleteModal(id) {
            deleteForm.action = `{{ url('/manage/post') }}/${id}`;
            deleteModal.showModal();
        }

        function showPreview(fileElement, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';

            const file = fileElement.files[0];
            if (!file) return;

            const url = URL.createObjectURL(file);

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = url;
                img.classList.add('max-w-xs', 'rounded', 'shadow');
                preview.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = url;
                video.controls = true;
                video.classList.add('max-w-xs', 'rounded', 'shadow');
                preview.appendChild(video);
            }
        }

        function findFileType(fileElement, selectId) {
            // find form
            let form = fileElement.parentElement
            while (true) {
                if (form.tagName == 'FORM') {
                    break
                }
                form = form.parentElement
            }


            const typeTag = form.type
            const durationTag = form.duration
            const muteTag = form.is_mute
            const playUntilStopTag = form.full_watch
            let fileType

            //reset default
            typeTag.value = null
            durationTag.value = 5
            muteTag.disabled = true
            playUntilStopTag.disabled = true
            muteTag.checked = false
            playUntilStopTag.checked = false
            durationTag.disabled = false

            const file = fileElement.files[0];
            if (!file) return;

            const url = URL.createObjectURL(file);
            if (file.type.startsWith('image/')) fileType = 'image'
            else if (file.type.startsWith('video/')) fileType = 'video'

            if (!playUntilStopTag.dataset.event) {
                playUntilStopTag.addEventListener('change', (e) => {
                    playUntilStopTag.dataset.event = true
                    if (playUntilStopTag.checked) {
                        durationTag.disabled = true
                        durationTag.value = null
                    } else {
                        durationTag.removeAttribute('disabled')
                    }
                })
            }

            typeTag.value = fileType
            if (fileType == 'image') {
                muteTag.setAttribute('disabled', true)
                playUntilStopTag.setAttribute('disabled', true)
            } else if (fileType == 'video') {
                durationTag.disabled = true
                durationTag.value = null
                muteTag.disabled = false
                playUntilStopTag.disabled = false
                playUntilStopTag.checked = true
            }


        }

        function findForm(element) {
            let temp = element.parentElement
            if (temp.tagName == 'FROM') {
                return temp
            }
            findForm(temp)
        }

        /* ---------- เปิดโมดัล ---------- */
        function openBinModal() {
            binModal.showModal();
        }

        /* ---------- ส่ง restore / delete ทีละหลายรายการ ---------- */
        let binAction = null; // เก็บ action ชั่วคราว
        let selectedIds = [];

        function submitBin(action) {
            const ids = [...document.querySelectorAll('.item-check:checked')]
                .map(cb => cb.value);

            if (ids.length === 0) {
                alertMessage('error', 'กรุณาเลือกโพสต์อย่างน้อย 1 รายการ');
                return;
            }

            binAction = action;
            selectedIds = ids;

            // ✅ ปิด binModal ก่อนเปิด confirm
            if (typeof binModal !== 'undefined') {
                binModal.close();
            }

            // เปลี่ยนข้อความตาม action
            const actionText = action === 'restore' ? 'กู้คืน' : 'ลบถาวร';
            document.getElementById('confirm-bin-message').innerText =
                `คุณแน่ใจหรือไม่ว่าต้องการ"${actionText}"โพสต์ที่เลือก?`;

            // เปิด modal
            document.getElementById('confirm-bin-modal').checked = true;
        }

        function submitConfirmedBin() {
            const url = binAction === 'restore' ?
                '{{ route('manage.post.restore') }}' :
                '{{ route('manage.post.delete') }}';

            const method = binAction === 'restore' ? 'POST' : 'DELETE';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alertMessage('success', data.message || 'ดำเนินการสำเร็จ');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        alertMessage('error', data.message || 'เกิดข้อผิดพลาด');
                    }
                })
                .catch(() => {
                    alertMessage('error', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                });
        }
    </script>

    <script>
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `alert alert-${type}`;
            toast.innerHTML = `
            <span>${message}</span>
        `;
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                alertMessage('success', "{{ session('success') }}")
            @endif

            @if (session('error'))
                alertMessage('error', "{{ session('error') }}")
            @endif
        })
    </script>
</x-app-layout>
