<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('อัปเดตรหัสผ่าน') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('เพื่อให้มั่นใจว่าบัญชีของคุณปลอดภัยอยู่เสมอ ควรใช้รหัสผ่านที่ยาวและคาดเดายาก รหัสผ่านที่ซับซ้อนจะช่วยป้องกันการเข้าถึงบัญชีของคุณโดยไม่ได้รับอนุญาตได้ดีที่สุดครับ') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('รหัสผ่านปัจจุบัน')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('รหัสใหม่')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('รหัสใหม่ (ยืนยัน)')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('บันทึก') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
