@extends('layouts.app')

@section('title', $title ?? 'Xác thực OTP')

@section('content')
<div class="min-h-screen relative overflow-hidden bg-[#121414] flex flex-col items-center justify-center p-6">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[#00ff66]/5 rounded-full blur-[120px]"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-[#00ff66]/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png')"></div>
    </div>

    <!-- Main OTP Card -->
    <div class="w-full max-w-[480px] relative z-10">
        <div class="bg-slate-900/40 backdrop-blur-md p-10 md:p-12 border border-white/10 shadow-2xl relative overflow-hidden group">
            <!-- Decorative Glow -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#00ff66]/10 blur-3xl pointer-events-none"></div>
            
            <!-- Header -->
            <div class="text-center mb-10">
                <h2 class="text-4xl md:text-5xl font-black font-space tracking-tighter text-lime-400 uppercase mb-2 glow-text">
                    {{ $title ?? 'Xác Thực OTP' }}
                </h2>
                <div class="h-0.5 w-12 bg-lime-400 mx-auto mb-2 opacity-50"></div>
            </div>


            <!-- OTP Form -->
            <form method="POST" action="{{ $sendAction ?? '#' }}" class="space-y-6">
                @csrf

                <!-- OTP Input -->
                <div class="space-y-2">
                    <label class="text-[10px] font-space font-bold tracking-[0.2em] uppercase text-zinc-500 ml-1">
                        Mã OTP
                    </label>
                    <div class="relative group/field">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="key-round" class="text-zinc-600 group-focus-within/field:text-lime-400 transition-colors w-5 h-5"></i>
                        </div>
                        <input 
                            type="text" 
                            name="otp" 
                            maxlength="6" 
                            inputmode="numeric" 
                            placeholder="000000"
                            value="{{ old('otp') }}"
                            class="w-full bg-slate-950/80 border border-white/5 py-4 pl-12 pr-4 text-center font-space text-2xl font-bold tracking-[0.25em] text-lime-400 placeholder-zinc-700 focus:outline-none focus:border-lime-400/50 focus:bg-slate-950 transition-all @error('otp') border-red-500 @enderror"
                            required
                            autofocus
                        />
                        <div class="absolute bottom-0 left-0 h-[1px] bg-lime-400 w-0 group-focus-within/field:w-full transition-all duration-500"></div>
                    </div>
                    @error('otp')
                        <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Display (Optional) -->
                @if(!empty($email))
                <div class="p-4 bg-slate-950/60 border border-white/5 space-y-1">
                    <p class="text-[9px] text-zinc-500 uppercase tracking-widest font-space font-bold">
                        Email xác thực
                    </p>
                    <p class="text-sm font-space text-zinc-300 break-all font-semibold">
                        {{ $email }}
                    </p>
                </div>
                @endif

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-lime-400 text-slate-950 py-5 font-space font-black text-sm tracking-[0.25em] uppercase shadow-[0_0_20px_rgba(0,255,102,0.3)] hover:shadow-[0_0_40px_rgba(0,255,102,0.4)] hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group"
                >
                    <span class="relative z-10">Xác thực OTP</span>
                    <i data-lucide="check" class="relative z-10 w-5 h-5"></i>
                    <div class="absolute inset-0 bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>

                <!-- Resend OTP Link -->
                <div class="text-center pt-2">
                    <p class="text-xs font-space font-bold uppercase tracking-wider text-zinc-500">
                        Không nhận được mã?
                        <button type="button" id="resend-button" onclick="resendOtp();" class="text-lime-400 hover:text-white transition-colors duration-300 bg-transparent border-none cursor-pointer p-0 ml-1">
                            Gửi lại
                        </button>
                    </p>
                </div>

            </form>

            <!-- Resend OTP Form (Outside main form) -->
            <form method="POST" action="{{ $resendAction ?? '#' }}" style="display: none;" id="resend-form">
                @csrf
            </form>

            <!-- Resend Cooldown (1 minute) -->
            <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-center text-xs font-space font-bold uppercase tracking-wider text-zinc-500">
                <div class="flex items-center gap-2">
                    <span id="resend-label" style="display:none">Gửi lại sau</span>
                    <span id="otp-timer" class="text-lime-400 font-bold"></span>
                </div>
            </div>

        </div>

        <!-- Footer Info -->
        <div class="mt-4 text-center text-[10px] uppercase font-bold tracking-widest text-zinc-600 space-y-1 font-space">
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
