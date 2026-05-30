{{-- VNTech AI Chatbot Widget - Nhúng vào layouts/app.blade.php trước </body> --}}
<div id="vntech-chatbot-widget" class="fixed bottom-6 right-6 z-[9999] font-inter antialiased">

    {{-- Floating Action Button (FAB) --}}
    <button id="chatbot-toggle-btn"
            title="Trò chuyện với trợ lý VNTech AI"
            class="relative w-14 h-14 rounded-full flex items-center justify-center text-black bg-lime-400 hover:bg-lime-300 shadow-[0_0_24px_rgba(163,230,53,0.4)] hover:shadow-[0_0_36px_rgba(163,230,53,0.65)] hover:scale-110 active:scale-95 transition-all duration-300 cursor-pointer">
        {{-- AI badge --}}
        <span class="absolute -top-1.5 -right-1.5 bg-lime-400 text-black text-[7px] font-black px-1.5 py-0.5 rounded-full border-2 border-[#121414] animate-pulse uppercase tracking-widest">AI</span>
        {{-- Chat Icon --}}
        <svg id="icon-chat" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        {{-- Close Icon --}}
        <svg id="icon-close" class="w-6 h-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button> 

    {{-- Chat Window --}}
    <div id="chatbot-window"
         class="absolute bottom-20 right-0 w-[380px] max-w-[calc(100vw-24px)] h-[540px] rounded-2xl flex flex-col overflow-hidden shadow-2xl border border-white/10 bg-[#0e1010]/95 backdrop-blur-xl
                opacity-0 scale-95 translate-y-4 pointer-events-none transition-all duration-300 ease-out">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-white/10 bg-[#0e1010]/80 shrink-0">
            <div class="flex items-center gap-3">
                {{-- Avatar + Status --}}
                <div class="relative w-10 h-10 shrink-0">
                    <div class="w-full h-full rounded-full bg-lime-400/10 border border-lime-400/40 flex items-center justify-center">
                        <svg class="w-5 h-5 text-lime-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-lime-400 border-2 border-[#0e1010] animate-pulse"></span>
                </div>
                <div class="leading-tight">
                    <h4 class="text-sm font-bold text-white">VNTech Assistant</h4>
                    <p class="text-[11px] text-lime-400/80 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-lime-400 inline-block animate-pulse"></span>
                        Trực tuyến · Hỗ trợ tư vấn AI
                    </p>
                </div>
            </div>
            {{-- Clear Button --}}
            <button id="chatbot-clear-btn"
                    title="Cuộc hội thoại mới"
                    class="text-slate-500 hover:text-white p-2 rounded-lg hover:bg-white/5 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>

        {{-- Messages Container --}}
        <div id="chatbot-messages" class="flex-1 flex flex-col gap-3 p-5 overflow-y-auto overscroll-contain scrollbar-thin">
        </div>

        {{-- Input Area --}}
        <div class="shrink-0 p-4 border-t border-white/10 bg-[#0e1010]/80">
            <form id="chatbot-input-form" autocomplete="off" class="flex gap-2.5 items-end">
                <input type="text"
                       id="chatbot-input-field"
                       placeholder="Nhập câu hỏi của bạn..."
                       required
                       class="flex-1 bg-white/5 border border-white/10 focus:border-lime-400/60 focus:ring-2 focus:ring-lime-400/10 text-white placeholder-slate-500 text-sm px-4 py-2.5 rounded-xl outline-none transition-all duration-200 resize-none">
                <button type="submit"
                        id="chatbot-send-btn"
                        class="w-10 h-10 rounded-xl bg-lime-400 hover:bg-lime-300 flex items-center justify-center text-black transition-all duration-200 hover:scale-105 active:scale-95 shrink-0 disabled:bg-slate-700 disabled:text-slate-500 disabled:cursor-not-allowed disabled:scale-100">
                    <svg class="w-5 h-5 rotate-45 -translate-x-px translate-y-px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
            <p class="text-[10px] text-slate-600 text-center mt-2.5">Powered by VNTech AI · Dữ liệu sản phẩm thực</p>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar cho chat window */
    #chatbot-messages::-webkit-scrollbar { width: 4px; }
    #chatbot-messages::-webkit-scrollbar-track { background: transparent; }
    #chatbot-messages::-webkit-scrollbar-thumb { background: rgba(163,230,53,0.15); border-radius: 99px; }
    #chatbot-messages::-webkit-scrollbar-thumb:hover { background: rgba(163,230,53,0.3); }

    /* Animation cho tin nhắn mới xuất hiện */
    @keyframes chat-msg-in {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .chat-msg-in { animation: chat-msg-in 0.25s ease-out both; }

    .chatbot-markdown :where(p, ul, ol, table, blockquote, pre) { margin-top: 0.5rem; }
    .chatbot-markdown :where(p:first-child, ul:first-child, ol:first-child, table:first-child, blockquote:first-child, pre:first-child) { margin-top: 0; }
    .chatbot-markdown ul { list-style: disc; padding-left: 1.15rem; }
    .chatbot-markdown ol { list-style: decimal; padding-left: 1.15rem; }
    .chatbot-markdown li + li { margin-top: 0.2rem; }
    .chatbot-markdown strong { color: #bef264; font-weight: 700; }
    .chatbot-markdown a { color: #a3e635; text-decoration: underline; text-underline-offset: 2px; }
    .chatbot-markdown code { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.08); border-radius: 0.35rem; padding: 0.05rem 0.3rem; font-size: 0.9em; }
    .chatbot-markdown pre { overflow-x: auto; background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 0.75rem; padding: 0.75rem; }
    .chatbot-markdown pre code { background: transparent; border: 0; padding: 0; }
    .chatbot-markdown table { width: 100%; border-collapse: collapse; font-size: 0.9em; }
    .chatbot-markdown th, .chatbot-markdown td { border: 1px solid rgba(255,255,255,0.12); padding: 0.35rem 0.5rem; text-align: left; }
    .chatbot-markdown th { color: #bef264; background: rgba(255,255,255,0.06); }

    /* Typing dots animation */
    @keyframes typing-bounce {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40% { transform: scale(1); opacity: 1; }
    }
</style>

@if(session('clear_chatbot'))
    <script>
        localStorage.removeItem('vntech_conversation_id')
    </script>
@endif

@vite('resources/js/chatbot.js')
