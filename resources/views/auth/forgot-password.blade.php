<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('ลืมรหัสผ่านใช่ไหม? ไม่มีปัญหา แค่แจ้งอีเมลของคุณให้เราทราบ แล้วเราจะส่งลิงก์สำหรับรีเซ็ตรหัสผ่านไปให้ทางอีเมล เพื่อให้คุณสามารถตั้งรหัสผ่านใหม่ได้') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('อีเมล')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center gap-2 justify-end mt-4">
            <a class="btn btn-secondary" href="{{ route('login') }}">
                {{ __('ย้อนกลับ') }}
            </a>
            <button class="btn btn-primary">
                {{ __('ยืนยัน') }}
            </button>
        </div>
    </form>
</x-guest-layout>
