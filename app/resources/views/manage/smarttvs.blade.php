@section('title')
    จัดการโพสต์
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('จัดการ Smart TV') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white min-h-[70vh] shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Header Bar --}}
                    <div class="flex gap-3 flex-col sm:flex-row items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 ">
                                <span class="">รายการ Smart TV ทั้งหมด</span>
                            </h1>
                            <p class="text-gray-600 mt-1">จัดการสมาร์ททีวีและโพสต์ภายใน</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn btn-dash btn-secondary" onclick="binModal.showModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                </svg>
                                <span>ถังขยะ</span>
                                @if (count($deletedSmartTvs) > 0)
                                    <span class="badge badge-sm badge-secondary">{{ count($deletedSmartTvs) }}</span>
                                @endif
                            </button>
                            <button class="btn btn-primary" onclick="openModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                </svg>
                                <span>
                                    เพิ่ม Smart TV
                                </span>
                            </button>
                        </div>
                    </div>

                    <hr class="my-5">

                    {{-- Grid of SmartTVs --}}
                    <div class="grid md:grid-cols-3 gap-6">
                        @forelse ($smartTvs as $tv)
                            <div class="card border border-base-200 shadow-md">
                                <div class="card-body">
                                    <h2 class="card-title">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>{{ $tv->name }}</span>
                                    </h2>
                                    <p class="text-sm text-gray-500">
                                        สถานะ: <span class="{{ $tv->is_public ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $tv->is_public ? 'เผยแพร่' : 'ไม่เผยแพร่' }}
                                        </span>
                                        <br>
                                        จำนวนโพสต์: <span>{{ $tv->posts_count }}</span>
                                    </p>
                                    <div class="card-actions justify-between mt-4">
                                        <a href="{{ route('manage.smarttvs.show', $tv->id) }}"
                                            class="btn btn-sm btn-success">📺 จัดการโพสต์</a>
                                        <div class="flex gap-2">
                                            <button class="btn btn-sm btn-warning"
                                                onclick="openModal({{ $tv }})">แก้ไข</button>
                                            <button class="btn btn-sm btn-error"
                                                onclick="openModalDelete({{ $tv->id }}, '{{ $tv->name }}')">ลบ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="alert alert-info">ยังไม่มี Smart TV ใด ๆ</div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Modal: Add/Edit --}}
                    <dialog id="smarttvModal" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg" id="modalTitle">เพิ่ม Smart TV</h3>
                            <form id="smarttvForm" class="space-y-4 mt-4">
                                <input type="hidden" id="smarttv_id">
                                {{-- <div class="form-control">
                                    <label class="label">ชื่อ Smart TV</label>
                                    <input type="text" id="name" class="input input-bordered" required>
                                </div> --}}
                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">ชื่อ Smart TV</legend>
                                    <input required placeholder="ชื่อ Smart TV" type="text" id="name" name="name"
                                        class="input input-neutral w-full">
                                </fieldset>
                                <div class="form-control">
                                    <label class="cursor-pointer label">
                                        <span class="label-text">แสดงต่อสาธารณะ</span>
                                        <input type="checkbox" checked id="is_public" class="checkbox">
                                    </label>
                                </div>
                                <div class="modal-action">
                                    <button type="button" class="btn" onclick="smarttvModal.close()">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </dialog>

                    {{-- Bin Modal --}}
                    <dialog id="binModal" class="modal modal-bottom sm:modal-middle">
                        <div class="modal-box w-11/12 max-w-3xl">
                            <h3 class="font-bold text-lg mb-4">ถังขยะ</h3>
                            <form id="binForm"
                                class="space-y-3 max-h-80 bg-slate-50 p-3 shadow rounded-md overflow-y-auto">
                                @if (count($deletedSmartTvs) == 0)
                                    <div role="alert" class="alert alert-info alert-outline">
                                        ไม่พบรายการที่ลบ
                                    </div>
                                @endif
                                @foreach ($deletedSmartTvs as $tv)
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" class="checkbox bin-check" value="{{ $tv->id }}">
                                        <span class="truncate flex-1">{{ $tv->name }}</span>
                                    </label>
                                @endforeach
                            </form>
                            <div class="modal-action">
                                <button type="button" class="btn" onclick="binModal.close()">ยกเลิก</button>
                                <button type="button" class="btn btn-error"
                                    onclick="submitBin('delete')">ลบถาวร</button>
                                <button type="button" class="btn btn-success"
                                    onclick="submitBin('restore')">กู้คืน</button>
                            </div>
                        </div>
                    </dialog>

                    <!-- Delete Confirm Modal -->
                    <dialog id="deleteModal" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg">ยืนยันการลบ</h3>
                            <p class="py-4">คุณแน่ใจหรือไม่ว่าต้องการลบ Smart TV <span class="font-bold"
                                    id="spanSmartTvName"></span> ?</p>
                            <div class="modal-action">
                                <button type="submit" data-id='' onclick="deleteSmartTv(this)"
                                    class="btn btn-error">ยืนยันลบ</button>
                                <button type="button" onclick="deleteModal.close()" class="btn">ยกเลิก</button>
                            </div>
                        </div>
                    </dialog>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        const smarttvForm = document.getElementById('smarttvForm');
        const modalTitle = document.getElementById('modalTitle');

        function openModal(tv = null) {
            if (tv) {
                document.getElementById('smarttv_id').value = tv.id;
                document.getElementById('name').value = tv.name;
                document.getElementById('is_public').checked = tv.is_public;
                modalTitle.textContent = 'แก้ไข Smart TV';
            } else {
                smarttvForm.reset();
                document.getElementById('smarttv_id').value = '';
                modalTitle.textContent = 'เพิ่ม Smart TV';
            }
            smarttvModal.showModal();
        }

        smarttvForm.onsubmit = function(e) {
            e.preventDefault();
            const id = document.getElementById('smarttv_id').value;
            const name = document.getElementById('name').value;
            const is_public = document.getElementById('is_public').checked;

            const url = id ? `/manage/smarttvs/${id}` : `{{ route('manage.smarttvs.store') }}`;
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        name,
                        is_public
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alertMessage(data.success ? 'success' : 'error', data.message);
                    if (data.success) setTimeout(() => location.reload(), 1000);
                });
        }

        function openModalDelete(id, name) {
            deleteModal.showModal()
            deleteModal.querySelector('#spanSmartTvName').innerHTML = `${name}`
            deleteModal.querySelector('button[type=submit]').dataset.id = id
        }

        function deleteSmartTv(btn) {
            let id = btn.dataset.id
            if (!id) return alertMessage('error', 'เกิดข้อผิดพลาด')
            btn.disalbed = true

            // if (!confirm('ต้องการลบรายการนี้หรือไม่?')) return;

            fetch(`/manage/smarttvs/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alertMessage(data.success ? 'success' : 'error', data.message);
                    if (data.success) setTimeout(() => location.reload(), 1000);
                });
        }

        function submitBin(action) {
            const ids = [...document.querySelectorAll('.bin-check:checked')].map(cb => cb.value);
            if (ids.length === 0) {
                alertMessage('error', 'กรุณาเลือกอย่างน้อย 1 รายการ');
                return;
            }

            const url = action === 'restore' ?
                '{{ route('manage.smarttvs.restore') }}' :
                '{{ route('manage.smarttvs.forceDelete') }}';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        ids
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alertMessage(data.success ? 'success' : 'error', data.message);
                    if (data.success) setTimeout(() => location.reload(), 1000);
                });
        }
    </script>
</x-app-layout>
