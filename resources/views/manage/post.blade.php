@section('title')
    จัดการโพสต์
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('จัดการโพสต์') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-2xl font-bold flex gap-3 flex-col sm:flex-row items-center justify-between">
                        <span>
                            จัดการโพสต์
                        </span>
                        <button class="btn btn-primary" onclick="openCreateModal()">+ เพิ่มโพสต์ใหม่</button>
                    </h2>

                    <hr class="my-3">

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
                                                            onclick="openEditModal({{ $post->id }})">แก้ไข</a></li>
                                                    <li><a class="btn btn-sm btn-error"
                                                            onclick="openDeleteModal({{ $post->id }})">ลบ</a></li>
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
                                                <div class="font-semibold">{{ $post->caption ?: '(ไม่มีคำอธิบาย)' }}
                                                </div>
                                                <div class="text-sm text-gray-600">ระยะเวลาแสดงผล:
                                                    {{ $post->duration == -1 ? 'เล่นจนจบ' : $post->duration . ' วินาที' }}
                                                </div>
                                                <div class="text-sm text-gray-600">ประเภท:
                                                    {{ $translation[$post->type] }}</div>
                                                @if ($post->type == 'video')
                                                    <div class="text-sm text-gray-600">เสียง:
                                                        {{ $post->is_mute ? '❌' : '✅' }}</div>
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
                                                            onclick="openEditModal({{ $post->id }})">แก้ไข</a></li>
                                                    <li><a class="btn btn-sm btn-error"
                                                            onclick="openDeleteModal({{ $post->id }})">ลบ</a></li>
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
                        <button id="save-order" class="btn btn-success hidden">บันทึกลำดับ / การเปลี่ยนแปลง</button>
                    </div>

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

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">ไฟล์ ภาพ/วิดิโอ</legend>
                    <input id="createFile" required
                        onchange="showPreview(this, 'createPreviewFile'); findFileType(this, 'craeteFileType');"
                        type="file" name="file" accept="image/*,video/*"
                        class="input-neutral file-input file-input-bordered w-full">
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">คำอธิบายภาพ</legend>
                    <input required name="caption" type="text" class="input input-neutral w-full"
                        placeholder="คำอธิบาย" />
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">ประเภท</legend>
                    <input type="text" id="craeteFileType" required name="type"
                        placeholder="กรุณาอัพโหลดไฟล์ก่อน" class="input disabled input-neutral w-full" readonly>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">ระยะเวลาแสดงผล (วินาที)</legend>
                    <input type="number" value="5" name="duration" min="1" step="1"
                        class="input input-neutral w-full mb-2" placeholder="ระยะเวลา (วินาที)">
                </fieldset>


                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" checked name="is_public" class="checkbox"> สาธารณะ
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
                    <button type="reset">reset</button>
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
                <input type="text" name="caption" id="editCaption" class="input input-bordered w-full mb-2">
                <input type="file" name="file" class="file-input file-input-bordered w-full mb-2">
                <input type="number" name="duration" id="editDuration" class="input input-bordered w-full mb-2">
                <label class="flex items-center gap-2 mb-2">
                    <input type="checkbox" name="is_public" id="editPublic" class="checkbox"> สาธารณะ
                </label>
                <label class="flex items-center gap-2 mb-4">
                    <input type="checkbox" name="is_mute" id="editMute" class="checkbox"> ปิดเสียง
                </label>
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


    <!-- Include SortableJS หรือ drag & drop lib อื่น -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const publicList = document.getElementById('public-posts');
        const hiddenList = document.getElementById('hidden-posts');
        const saveBtn = document.getElementById('save-order');

        [publicList, hiddenList].forEach(list => {
            Sortable.create(list, {
                group: 'posts',
                animation: 150,
                onEnd: () => {
                    saveBtn.classList.remove('hidden');
                }
            });
        });

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
                        alert('บันทึกเรียบร้อย');
                    }
                });
        });


        function openCreateModal() {
            createModal.showModal();
        }

        function openEditModal(id) {

            fetch(`{{ url('/manage/post/') }}/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data?.success) {
                        data = data.post
                        editForm.action = `/manage/post/${id}`;
                        editCaption.value = data.caption || '';
                        editDuration.value = data.duration;
                        editPublic.checked = data.is_public;
                        editMute.checked = data.is_mute;
                        editModal.showModal();
                    } else {
                        alert('เกิดข้อผิดพลาด')
                    }
                }).catch(e => {
                    alert('เกิดข้อผิดพลาด')
                })
        }

        function openDeleteModal(id) {
            deleteForm.action = `/manage/post/${id}`;
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
    </script>
</x-app-layout>
