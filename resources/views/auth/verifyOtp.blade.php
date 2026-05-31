@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-950 via-gray-900 to-black flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md animate-fadeInUp">
        <!-- Card Container -->
        <div class="bg-gray-900/50 backdrop-blur-xl border border-neon-green/20 rounded-2xl p-8 shadow-2xl">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neon-green/10 border border-neon-green/30 mb-4">
                    <i data-lucide="mail-check" class="w-8 h-8 text-neon-green"></i>
                </div>
                <h1 class="font-space text-3xl font-bold text-gray-100 uppercase tracking-tight mb-2">
                    Xác thực OTP
                </h1>
                <p class="text-gray-400 text-sm">
                    Nhập mã 6 chữ số được gửi đến email của bạn
                </p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm flex flex-col gap-1.5 animate-fadeInUp">
                <div class="flex items-center gap-2 font-bold">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    <span>Có lỗi xảy ra:</span>
                </div>
                <ul class="list-disc list-inside pl-2 text-xs space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-neon-green/10 border border-neon-green/30 text-neon-green text-sm flex items-center gap-3 animate-fadeInUp">
                <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- OTP Form -->
            <form method="POST" action="{{ route('verify.otp') }}" class="space-y-6">
                @csrf

                <!-- OTP Input Container -->
                <div class="space-y-3">
                    <label class="block text-sm font-space font-bold text-gray-300 uppercase tracking-wide">
                        Mã OTP
                    </label>
                    
                    <!-- Single Input for OTP (6 digits) -->
                    <input 
                        type="text" 
                        name="otp" 
                        maxlength="6" 
                        inputmode="numeric" 
                        placeholder="000000"
                        value="{{ old('otp') }}"
                        class="w-full px-4 py-3 bg-gray-800/50 border border-neon-green/30 rounded-lg text-center font-space text-2xl font-bold text-neon-green placeholder-gray-600 focus:outline-none focus:border-neon-green focus:ring-2 focus:ring-neon-green/20 transition-all duration-300 tracking-widest"
                        required
                    >
                    <p class="text-xs text-gray-500 text-center">
                        Nhập 6 chữ số từ email của bạn
                    </p>
                </div>

                <!-- Email Display (Optional) -->
                @if(session('register_email'))
                <div class="p-3 rounded-lg bg-gray-800/30 border border-gray-700/50">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-space font-bold mb-1">
                        Email xác thực
                    </p>
                    <p class="text-sm text-gray-300 break-all">
                        {{ session('register_email') }}
                    </p>
                </div>
                @endif

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full px-4 py-3 bg-gradient-to-r from-neon-green to-emerald-400 text-gray-950 font-space font-bold uppercase tracking-wider rounded-lg hover:shadow-[0_0_20px_rgba(0,255,102,0.4)] transition-all duration-300 hover:scale-105 active:scale-95"
                >
                    <span class="flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-5 h-5"></i>
                        Xác thực OTP
                    </span>
                </button>

                <!-- Resend OTP Link -->
                <div class="text-center pt-2">
                    <p class="text-sm text-gray-400">
                        Không nhận được mã?
                        <button type="button" onclick="document.getElementById('resend-form').submit();" class="text-neon-green hover:text-emerald-400 font-bold transition-colors duration-300 bg-none border-none cursor-pointer p-0">
                            Gửi lại
                        </button>
                    </p>
                </div>

            </form>

            <!-- Resend OTP Form (Outside main form) -->
            <form method="POST" action="{{ route('resend.otp') }}" style="display: none;" id="resend-form">
                @csrf
            </form>

            <!-- Back to Register -->
            <div class="mt-6 pt-6 border-t border-gray-700/50">
                <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 text-sm text-gray-400 hover:text-gray-300 transition-colors duration-300">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Quay lại đăng ký</span>
                </a>
            </div>

            <!-- Timer (Optional - for frontend countdown) -->
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500 font-space">
                    Mã OTP hết hạn trong: <span id="otp-timer" class="text-neon-green font-bold">5:00</span>
                </p>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-6 text-center text-xs text-gray-500 space-y-1">
            <p>Mã OTP có hiệu lực trong 5 phút</p>
            <p>Không chia sẻ mã OTP với bất kỳ ai</p>
        </div>
    </div>
</div>

<!-- Timer Script (Optional) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerElement = document.getElementById('otp-timer');
        if (!timerElement) return;

        let timeLeft = 5 * 60; // 5 minutes in seconds

        const updateTimer = () => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                timerElement.textContent = 'Hết hạn';
                timerElement.classList.add('text-red-500');
                timerElement.classList.remove('text-neon-green');
            } else {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            }
        };

        updateTimer();

        // Auto-focus on OTP input
        const otpInput = document.querySelector('input[name="otp"]');
        if (otpInput) {
            otpInput.focus();
            // Only allow numbers
            otpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
</script>
@endsection
