@extends('layouts.app')

@section('title', 'Xac thuc doi email | VNTech')

@section('content')
<div class="min-h-screen relative overflow-hidden bg-[#121414] flex flex-col items-center justify-center p-6 py-12">
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[#00ff66]/5 rounded-full blur-[120px]"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-[#00ff66]/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png')"></div>
    </div>

    <div class="w-full max-w-[520px] relative z-10">
        <div class="bg-slate-900/40 backdrop-blur-md p-10 md:p-12 border border-white/10 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#00ff66]/10 blur-3xl pointer-events-none"></div>

            <div class="text-center mb-10">
                <h2 class="text-4xl md:text-5xl font-black font-space tracking-tighter text-lime-400 uppercase mb-2 glow-text">
                    Xác Thực Email
                </h2>
                <div class="h-0.5 w-12 bg-lime-400 mx-auto mb-2 opacity-50"></div>
            </div>

            <form method="POST" action="{{ route('user.email.change.verify') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="new_email" value="{{ $newEmail }}">

                <div class="p-4 bg-slate-950/60 border border-white/5 space-y-4">
                    <div>
                        <p class="text-[9px] text-zinc-500 uppercase tracking-widest font-space font-bold">Nhập mã OTP của email hiện tại</p>
                        <p class="text-sm font-space text-zinc-300 break-all font-semibold">{{ $oldEmail }}</p>
                    </div>

                    <div class="flex items-center justify-between gap-3 text-xs font-space font-bold uppercase tracking-wider text-zinc-500">
                        <button type="button" id="resend-old-email-button" onclick="submitResendOtp('old')" class="text-lime-400 hover:text-white transition-colors duration-300 bg-transparent border-none cursor-pointer p-0">
                            Gửi lại OTP
                        </button>
                        <span id="resend-old-email-timer" class="text-lime-400"></span>
                    </div>

                    <div class="space-y-2">
                        <div class="relative group/field">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="key-round" class="text-zinc-600 group-focus-within/field:text-lime-400 transition-colors w-5 h-5"></i>
                            </div>
                            <input type="text" name="old_email_otp" maxlength="6" inputmode="numeric" placeholder="000000" class="w-full bg-slate-950/80 border border-white/5 py-4 pl-12 pr-4 text-center font-space text-2xl font-bold tracking-[0.25em] text-lime-400 placeholder-zinc-700 focus:outline-none focus:border-lime-400/50 focus:bg-slate-950 transition-all" required autofocus>
                            <div class="absolute bottom-0 left-0 h-[1px] bg-lime-400 w-0 group-focus-within/field:w-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-950/60 border border-white/5 space-y-4">
                    <div>
                        <p class="text-[9px] text-zinc-500 uppercase tracking-widest font-space font-bold">Nhập mã OTP của email mới</p>
                        <p class="text-sm font-space text-zinc-300 break-all font-semibold">{{ $newEmail }}</p>
                    </div>

                    <div class="flex items-center justify-between gap-3 text-xs font-space font-bold uppercase tracking-wider text-zinc-500">
                        <button type="button" id="resend-new-email-button" onclick="submitResendOtp('new')" class="text-lime-400 hover:text-white transition-colors duration-300 bg-transparent border-none cursor-pointer p-0">
                            Gửi lại OTP
                        </button>
                        <span id="resend-new-email-timer" class="text-lime-400"></span>
                    </div>

                    <div class="space-y-2">
                        <div class="relative group/field">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="key-round" class="text-zinc-600 group-focus-within/field:text-lime-400 transition-colors w-5 h-5"></i>
                            </div>
                            <input type="text" name="new_email_otp" maxlength="6" inputmode="numeric" placeholder="000000" class="w-full bg-slate-950/80 border border-white/5 py-4 pl-12 pr-4 text-center font-space text-2xl font-bold tracking-[0.25em] text-lime-400 placeholder-zinc-700 focus:outline-none focus:border-lime-400/50 focus:bg-slate-950 transition-all" required>
                            <div class="absolute bottom-0 left-0 h-[1px] bg-lime-400 w-0 group-focus-within/field:w-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-lime-400 text-slate-950 py-5 font-space font-black text-sm tracking-[0.25em] uppercase shadow-[0_0_20px_rgba(0,255,102,0.3)] hover:shadow-[0_0_40px_rgba(0,255,102,0.4)] hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group">
                    <span class="relative z-10">Xác Nhận</span>
                    <i data-lucide="check" class="relative z-10 w-5 h-5"></i>
                    <div class="absolute inset-0 bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
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
            },
            new: {
                key: 'change_email_otp_resend_new_{{ md5($newEmail) }}',
                button: document.getElementById('resend-new-email-button'),
                timer: document.getElementById('resend-new-email-timer'),
                form: document.getElementById('resend-new-email-form'),
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
                return;
            }

            control.timer.textContent = formatTime(remaining);
            control.button.disabled = true;
            control.button.classList.add('opacity-50', 'cursor-not-allowed');
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
    });
</script>
@endsection
