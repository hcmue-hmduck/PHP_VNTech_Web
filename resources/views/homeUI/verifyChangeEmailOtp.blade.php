@extends('layouts.app')

@section('title', 'Xác thực đổi Email | VNTech')

@section('content')
<div class="min-h-[calc(100vh-102px)] relative overflow-hidden bg-[#FAF8F2] flex flex-col items-center justify-center p-6 py-12">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-brand-500/5 rounded-full blur-[120px]"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-brand-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="w-full max-w-[500px] relative z-10">
        <div class="bg-white border border-neutral-200/60 p-8 md:p-10 rounded-3xl shadow-xl shadow-slate-200/50 relative overflow-hidden">
            
            <div class="text-center mb-8">
                <h2 class="text-3xl font-display font-black tracking-tight text-neutral-900 uppercase">
                    Xác Thực Email
                </h2>
                <p class="text-neutral-500 text-xs font-semibold mt-2">Nhập mã OTP để xác nhận thay đổi địa chỉ email của bạn</p>
            </div>

            <!-- Errors/Notifications -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-650 rounded-2xl text-xs font-bold uppercase tracking-wider space-y-1 select-none">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center gap-1.5">
                            <i data-lucide="alert-triangle" class="w-4 h-4"></i> {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('user.email.change.verify') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="new_email" value="{{ $newEmail }}">

                <!-- Old Email OTP Section -->
                <div class="p-5 bg-neutral-50 border border-neutral-200/65 rounded-2xl space-y-4">
                    <div class="space-y-1">
                        <p class="text-[9px] text-neutral-400 uppercase tracking-widest font-bold">
                            Mã OTP gửi đến Email hiện tại
                        </p>
                        <p class="text-sm font-semibold text-neutral-700 break-all">
                            {{ $oldEmail }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                            <i data-lucide="key-round" class="w-3.5 h-3.5"></i> Nhập mã xác thực
                        </label>
                        <input 
                            type="text" 
                            name="old_email_otp" 
                            maxlength="6" 
                            inputmode="numeric" 
                            placeholder="000000"
                            class="w-full bg-white border border-neutral-200 hover:border-neutral-300 focus:border-brand-500 focus:bg-white text-center font-display text-2xl font-bold tracking-[0.25em] text-brand-500 placeholder-neutral-300 rounded-xl py-3 px-4 focus:ring-2 focus:ring-brand-500/20 transition-all focus:outline-none"
                            required 
                            autofocus
                        />
                    </div>

                    <div class="flex items-center justify-between text-xs font-semibold text-neutral-400 pt-3 border-t border-neutral-200/60">
                        <button type="button" id="resend-old-email-button" onclick="submitResendOtp('old')" class="text-brand-500 hover:text-brand-600 font-bold hover:underline bg-transparent border-none cursor-pointer p-0">
                            Gửi lại OTP
                        </button>
                        <div class="flex items-center gap-1">
                            <span id="resend-old-label" style="display:none" class="text-neutral-400">Gửi lại sau</span>
                            <span id="resend-old-email-timer" class="text-brand-500 font-bold"></span>
                        </div>
                    </div>
                </div>

                <!-- New Email OTP Section -->
                <div class="p-5 bg-neutral-50 border border-neutral-200/65 rounded-2xl space-y-4">
                    <div class="space-y-1">
                        <p class="text-[9px] text-neutral-400 uppercase tracking-widest font-bold">
                            Mã OTP gửi đến Email mới
                        </p>
                        <p class="text-sm font-semibold text-neutral-700 break-all">
                            {{ $newEmail }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                            <i data-lucide="key-round" class="w-3.5 h-3.5"></i> Nhập mã xác thực
                        </label>
                        <input 
                            type="text" 
                            name="new_email_otp" 
                            maxlength="6" 
                            inputmode="numeric" 
                            placeholder="000000"
                            class="w-full bg-white border border-neutral-200 hover:border-neutral-300 focus:border-brand-500 focus:bg-white text-center font-display text-2xl font-bold tracking-[0.25em] text-brand-500 placeholder-neutral-300 rounded-xl py-3 px-4 focus:ring-2 focus:ring-brand-500/20 transition-all focus:outline-none"
                            required
                        />
                    </div>

                    <div class="flex items-center justify-between text-xs font-semibold text-neutral-400 pt-3 border-t border-neutral-200/60">
                        <button type="button" id="resend-new-email-button" onclick="submitResendOtp('new')" class="text-brand-500 hover:text-brand-600 font-bold hover:underline bg-transparent border-none cursor-pointer p-0">
                            Gửi lại OTP
                        </button>
                        <div class="flex items-center gap-1">
                            <span id="resend-new-label" style="display:none" class="text-neutral-400">Gửi lại sau</span>
                            <span id="resend-new-email-timer" class="text-brand-500 font-bold"></span>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white py-3.5 px-6 font-display font-bold text-sm uppercase rounded-xl transition-all shadow-md shadow-brand-500/10 active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                    <span>Xác Nhận Thay Đổi</span>
                    <i data-lucide="check" class="w-4 h-4"></i>
                </button>
            </form>

            <form method="POST" action="{{ route('user.email.change.resend') }}" id="resend-old-email-form" class="hidden">
                @csrf
                <input type="hidden" name="email" value="{{ $oldEmail }}">
            </form>

            <form method="POST" action="{{ route('user.email.change.resend') }}" id="resend-new-email-form" class="hidden">
                @csrf
                <input type="hidden" name="email" value="{{ $newEmail }}">
            </form>
        </div>

        <!-- Footer Info -->
        <div class="mt-6 text-center text-[10px] uppercase font-bold tracking-widest text-neutral-400">
            <p>Tuyệt đối không chia sẻ mã OTP với bất kỳ ai</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cooldownSeconds = 60;
        const resendControls = {
            old: {
                key: 'change_email_otp_resend_old_{{ md5($oldEmail) }}',
                button: document.getElementById('resend-old-email-button'),
                timer: document.getElementById('resend-old-email-timer'),
                form: document.getElementById('resend-old-email-form'),
                label: document.getElementById('resend-old-label')
            },
            new: {
                key: 'change_email_otp_resend_new_{{ md5($newEmail) }}',
                button: document.getElementById('resend-new-email-button'),
                timer: document.getElementById('resend-new-email-timer'),
                form: document.getElementById('resend-new-email-form'),
                label: document.getElementById('resend-new-label')
            },
        };

        function getRemaining(key) {
            const expiry = parseInt(localStorage.getItem(key) || '0', 10);
            const remaining = Math.ceil((expiry - Date.now()) / 1000);
            return remaining > 0 ? remaining : 0;
        }

        function setExpiry(key) {
            localStorage.setItem(key, String(Date.now() + cooldownSeconds * 1000));
        }

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60).toString().padStart(2, '0');
            const rest = (seconds % 60).toString().padStart(2, '0');
            return `${minutes}:${rest}`;
        }

        function updateControl(type) {
            const control = resendControls[type];
            if (!control || !control.button || !control.timer) return;

            const remaining = getRemaining(control.key);
            if (remaining <= 0) {
                localStorage.removeItem(control.key);
                control.timer.textContent = '';
                control.button.disabled = false;
                control.button.classList.remove('opacity-50', 'cursor-not-allowed');
                if (control.label) control.label.style.display = 'none';
                return;
            }

            control.timer.textContent = formatTime(remaining);
            control.button.disabled = true;
            control.button.classList.add('opacity-50', 'cursor-not-allowed');
            if (control.label) control.label.style.display = 'inline';
            setTimeout(function () {
                updateControl(type);
            }, 1000);
        }

        window.submitResendOtp = function (type) {
            const control = resendControls[type];
            if (!control || !control.form || getRemaining(control.key) > 0) return;

            setExpiry(control.key);
            updateControl(type);
            control.form.submit();
        };

        updateControl('old');
        updateControl('new');

        // Text input validation (Numeric only)
        const oldOtpInput = document.querySelector('input[name="old_email_otp"]');
        const newOtpInput = document.querySelector('input[name="new_email_otp"]');
        if (oldOtpInput) {
            oldOtpInput.focus();
            oldOtpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
        if (newOtpInput) {
            newOtpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
</script>
@endsection
