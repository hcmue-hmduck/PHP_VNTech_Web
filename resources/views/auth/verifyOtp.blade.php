@extends('layouts.app')

@section('title', $title ?? 'Xác thực OTP')

@section('content')
<div class="min-h-[calc(100vh-102px)] relative overflow-hidden bg-[#FAF8F2] flex flex-col items-center justify-center p-6 py-12">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-brand-500/5 rounded-full blur-[120px]"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-brand-500/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- Main OTP Card -->
    <div class="w-full max-w-[460px] relative z-10">
        <div class="bg-white border border-neutral-200/60 p-8 md:p-10 rounded-3xl shadow-xl shadow-slate-200/50 relative overflow-hidden">
            
            <div class="text-center mb-8">
                <h2 class="text-3xl font-display font-black tracking-tight text-neutral-900 uppercase">
                    {{ $title ?? 'Xác Thực OTP' }}
                </h2>
                <p class="text-neutral-500 text-xs font-semibold mt-2">Vui lòng nhập mã OTP đã được gửi để xác thực</p>
            </div>

            <!-- OTP Form -->
            <form method="POST" action="{{ $sendAction ?? '#' }}" class="space-y-6">
                @csrf

                <!-- OTP Input -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest flex items-center gap-1.5 select-none">
                        <i data-lucide="key-round" class="w-3.5 h-3.5"></i> Mã OTP
                    </label>
                    <input 
                        type="text" 
                        name="otp" 
                        maxlength="6" 
                        inputmode="numeric" 
                        placeholder="000000"
                        value="{{ old('otp') }}"
                        class="w-full bg-neutral-50 border border-neutral-200 hover:border-neutral-300 focus:border-brand-500 focus:bg-white text-center font-display text-2xl font-bold tracking-[0.25em] text-brand-500 placeholder-neutral-300 rounded-xl py-3.5 px-4 focus:ring-2 focus:ring-brand-500/20 transition-all focus:outline-none @error('otp') border-red-500 @enderror"
                        required
                        autofocus
                    />
                    @error('otp')
                        <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Display (Optional) -->
                @if(!empty($email))
                <div class="p-4 bg-neutral-50 border border-neutral-200/65 rounded-2xl space-y-1">
                    <p class="text-[9px] text-neutral-400 uppercase tracking-widest font-bold">
                        Email xác thực
                    </p>
                    <p class="text-sm font-semibold text-neutral-700 break-all">
                        {{ $email }}
                    </p>
                </div>
                @endif

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white py-3.5 px-6 font-display font-bold text-sm uppercase rounded-xl transition-all shadow-md shadow-brand-500/10 active:scale-95 flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span>Xác thực OTP</span>
                    <i data-lucide="check" class="w-4 h-4"></i>
                </button>

                <!-- Resend OTP Link -->
                <div class="text-center pt-2">
                    <p class="text-xs font-semibold text-neutral-500">
                        Không nhận được mã?
                        <button type="button" id="resend-button" onclick="resendOtp();" class="text-brand-500 hover:text-brand-600 font-bold ml-1 hover:underline bg-transparent border-none cursor-pointer p-0">
                            Gửi lại
                        </button>
                    </p>
                </div>

            </form>

            <!-- Resend OTP Form (Outside main form) -->
            <form method="POST" action="{{ $resendAction ?? '#' }}" style="display: none;" id="resend-form">
                @csrf
            </form>

            <!-- Resend Cooldown -->
            <div class="mt-6 pt-5 border-t border-neutral-100 flex items-center justify-center text-xs font-semibold text-neutral-400">
                <div class="flex items-center gap-2">
                    <span id="resend-label" style="display:none">Gửi lại sau</span>
                    <span id="otp-timer" class="text-brand-500 font-bold"></span>
                </div>
            </div>

        </div>

        <!-- Footer Info -->
        <div class="mt-6 text-center text-[10px] uppercase font-bold tracking-widest text-neutral-400">
            <p>Không chia sẻ mã OTP với bất kỳ ai</p>
        </div>
    </div>
</div>

<!-- Timer Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const COOLDOWN_KEY = 'otp_resend_expiry';
        const timerElement = document.getElementById('otp-timer');
        const resendButton = document.getElementById('resend-button');
        if (!timerElement || !resendButton) return;

        function setExpiry(seconds) {
            const expiry = Date.now() + seconds * 1000;
            localStorage.setItem(COOLDOWN_KEY, String(expiry));
        }

        function getRemaining() {
            const raw = localStorage.getItem(COOLDOWN_KEY);
            if (!raw) return 0;
            const remaining = Math.ceil((parseInt(raw, 10) - Date.now()) / 1000);
            return remaining > 0 ? remaining : 0;
        }

        function updateTimer() {
            const remaining = getRemaining();
            const label = document.getElementById('resend-label');
            if (remaining <= 0) {
                localStorage.removeItem(COOLDOWN_KEY);
                timerElement.textContent = '';
                resendButton.disabled = false;
                resendButton.classList.remove('opacity-50', 'cursor-not-allowed');
                if (label) label.style.display = 'none';
            } else {
                const minutes = Math.floor(remaining / 60).toString().padStart(2, '0');
                const seconds = (remaining % 60).toString().padStart(2, '0');
                timerElement.textContent = `${minutes}:${seconds}`;
                resendButton.disabled = true;
                resendButton.classList.add('opacity-50', 'cursor-not-allowed');
                if (label) label.style.display = 'inline';
                setTimeout(updateTimer, 1000);
            }
        }

        // Called when user clicks resend
        window.resendOtp = function() {
            if (getRemaining() > 0) return;
            setExpiry(60); // 1 minute cooldown
            // submit the hidden resend form
            document.getElementById('resend-form').submit();
            updateTimer();
        }

        // Initialize timer on load
        updateTimer();

        // Auto-focus on OTP input
        const otpInput = document.querySelector('input[name="otp"]');
        if (otpInput) {
            otpInput.focus();
            otpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
</script>
@endsection
