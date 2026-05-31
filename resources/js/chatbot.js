import { marked } from "marked";
import DOMPurify from "dompurify";

document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("chatbot-toggle-btn");
    const chatWindow = document.getElementById("chatbot-window");
    const clearBtn = document.getElementById("chatbot-clear-btn");
    const inputField = document.getElementById("chatbot-input-field");
    const inputForm = document.getElementById("chatbot-input-form");
    const sendBtn = document.getElementById("chatbot-send-btn");
    const messages = document.getElementById("chatbot-messages");
    const iconChat = document.getElementById("icon-chat");
    const iconClose = document.getElementById("icon-close");
    const widget = document.getElementById("vntech-chatbot-widget");

    const STORAGE_KEY = "vntech_conversation_id";
    const POS_KEY = "vntech_chatbot_pos";
    const WELCOME_MESSAGE = "Chào mừng đến với **VNTech**! 👋\nEm là trợ lý AI tư vấn phần cứng và công nghệ. Anh/chị cần tìm kiếm sản phẩm hay cần tư vấn gì ạ?";

    function showWelcomeMessage() {
        addMessage(WELCOME_MESSAGE, "bot", false);
    }

    if (
        !toggleBtn ||
        !chatWindow ||
        !clearBtn ||
        !inputField ||
        !inputForm ||
        !sendBtn ||
        !messages
    ) {
        return;
    }

    marked.setOptions({
        breaks: true,
        gfm: true,
    });

    // ── Kéo thả widget (chỉ lên/xuống, cố định bên phải) ───
    (function initDrag() {
        const MARGIN = 24;

        // Phục hồi vị trí bottom đã lưu, hoặc dùng mặc định
        const savedBottom = localStorage.getItem(POS_KEY);
        const initBottom = savedBottom !== null
            ? Math.min(Math.max(MARGIN, Number(savedBottom)), window.innerHeight - widget.offsetHeight - MARGIN)
            : MARGIN;

        // Widget luôn cố định bên phải, chỉ thay đổi bottom
        widget.style.position = 'fixed';
        widget.style.right  = MARGIN + 'px';
        widget.style.bottom = initBottom + 'px';

        let isDragging = false;
        let startY     = 0;
        let startBottom = 0;
        const DRAG_THRESHOLD = 5;

        toggleBtn.addEventListener('mousedown', onDragStart);
        toggleBtn.addEventListener('touchstart', onDragStart, { passive: true });

        function onDragStart(e) {
            const point = e.touches ? e.touches[0] : e;
            startY = point.clientY;
            startBottom = parseFloat(widget.style.bottom) || MARGIN;
            isDragging = false;

            document.addEventListener('mousemove', onDragMove);
            document.addEventListener('mouseup',   onDragEnd);
            document.addEventListener('touchmove', onDragMove, { passive: false });
            document.addEventListener('touchend',  onDragEnd);
        }

        function onDragMove(e) {
            const point = e.touches ? e.touches[0] : e;
            const dy = Math.abs(point.clientY - startY);

            if (!isDragging && dy < DRAG_THRESHOLD) return;

            if (!isDragging) {
                isDragging = true;
                widget.style.transition = 'none';
                toggleBtn.style.cursor  = 'grabbing';
            }

            if (e.cancelable) e.preventDefault();

            // Kéo lên => clientY giảm => bottom tăng (và ngược lại)
            const delta  = startY - point.clientY;
            const maxBottom = window.innerHeight - widget.offsetHeight - MARGIN;
            const newBottom = Math.max(MARGIN, Math.min(maxBottom, startBottom + delta));

            widget.style.bottom = newBottom + 'px';
        }

        function onDragEnd() {
            document.removeEventListener('mousemove', onDragMove);
            document.removeEventListener('mouseup',   onDragEnd);
            document.removeEventListener('touchmove', onDragMove);
            document.removeEventListener('touchend',  onDragEnd);

            widget.style.transition = '';
            toggleBtn.style.cursor  = 'pointer';

            if (!isDragging) return; // click thường → bỏ qua

            // Lưu vị trí bottom vào localStorage
            localStorage.setItem(POS_KEY, parseFloat(widget.style.bottom));
        }
    })();

    // ── Khởi tạo: load lịch sử cũ nếu có ───────────────────
    loadHistory();

    async function loadHistory() {
        const convId = localStorage.getItem(STORAGE_KEY) || "";
        
        // Luôn hiển thị tin chào mặc định ở trên cùng
        showWelcomeMessage();

        try {
            const res = await fetch(`/chat/history?conversation_id=${convId}`, {
                headers: { Accept: "application/json" },
            });
            const data = await res.json();

            if (data.success) {
                // Nếu backend trả về conversation_id mới (hoặc cũ), hãy lưu lại
                if (data.conversation_id) {
                    localStorage.setItem(STORAGE_KEY, data.conversation_id);
                }

                // Nếu có lịch sử tin nhắn
                if (data.messages?.length) {
                    data.messages.forEach((m) =>
                        addMessage(
                            m.content,
                            m.role === "user" ? "user" : "bot",
                            false,
                        ),
                    );
                    scrollBottom();
                }
            }
        } catch (e) {
            /* bỏ qua lỗi load history */
        }
    }

    // ── Toggle mở / đóng khung chat ─────────────────────────
    toggleBtn.addEventListener("click", () => {
        const isOpen = chatWindow.classList.contains("opacity-100");
        if (isOpen) {
            chatWindow.classList.replace("opacity-100", "opacity-0");
            chatWindow.classList.replace("scale-100", "scale-95");
            chatWindow.classList.replace("translate-y-0", "translate-y-4");
            chatWindow.classList.replace(
                "pointer-events-auto",
                "pointer-events-none",
            );
            iconChat?.classList.remove("hidden");
            iconClose?.classList.add("hidden");
        } else {
            chatWindow.classList.replace("opacity-0", "opacity-100");
            chatWindow.classList.replace("scale-95", "scale-100");
            chatWindow.classList.replace("translate-y-4", "translate-y-0");
            chatWindow.classList.replace(
                "pointer-events-none",
                "pointer-events-auto",
            );
            iconChat?.classList.add("hidden");
            iconClose?.classList.remove("hidden");
            inputField.focus();
            scrollBottom();
        }
    });

    // ── Xoá hội thoại, bắt đầu mới ──────────────────────────
    clearBtn.addEventListener("click", async () => {
        if (!confirm("Bắt đầu cuộc hội thoại mới?")) return;

        const convId = localStorage.getItem(STORAGE_KEY);
        if (convId) {
            try {
                await fetch("/chat/clear", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN":
                            document.querySelector('meta[name="csrf-token"]')
                                ?.content ?? "",
                    },
                    body: JSON.stringify({
                        conversation_id: convId,
                    }),
                });
            } catch (e) {
                console.error("Lỗi khi xóa cuộc hội thoại:", e);
            }
        }

        localStorage.removeItem(STORAGE_KEY);
        messages.innerHTML = "";
        showWelcomeMessage();
    });

    // ── Gửi tin nhắn ────────────────────────────────────────
    inputForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const text = inputField.value.trim();
        if (!text) return;

        addMessage(text, "user");
        inputField.value = "";
        setLoading(true);
        const typing = addTyping();

        try {
            const res = await fetch("/chat", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN":
                        document.querySelector('meta[name="csrf-token"]')
                            ?.content ?? "",
                },
                body: JSON.stringify({
                    message: text,
                    conversation_id: localStorage.getItem(STORAGE_KEY),
                }),
            });

            const data = await res.json();
            typing.remove();

            if (!res.ok || !data.success) {
                addMessage(
                    data.message ?? "Đã xảy ra lỗi, vui lòng thử lại.",
                    "error",
                );
            } else {
                if (data.conversation_id) {
                    localStorage.setItem(STORAGE_KEY, data.conversation_id);
                }
                addMessage(data.message, "bot");
            }
        } catch (err) {
            typing.remove();
            addMessage(
                "Mất kết nối đến máy chủ. Vui lòng thử lại sau.",
                "error",
            );
            console.log(err);
        } finally {
            setLoading(false);
            inputField.focus();
        }
    });

    // ── Helpers ──────────────────────────────────────────────
    function addMessage(text, role, animate = true) {
        const wrapper = document.createElement("div");
        wrapper.className = `flex flex-col ${role === "user" ? "items-end self-end" : "items-start self-start"} max-w-[82%] ${animate ? "chat-msg-in" : ""}`;

        const bubble = document.createElement("div");
        bubble.className =
            "text-[13px] leading-relaxed px-4 py-3 rounded-2xl " +
            (role === "user"
                ? "bg-lime-400 text-black font-medium rounded-tr-sm shadow-[0_0_12px_rgba(163,230,53,0.2)]"
                : role === "error"
                  ? "bg-red-500/10 text-red-400 border border-red-500/20 rounded-tl-sm"
                  : "bg-white/5 text-slate-200 border border-white/10 rounded-tl-sm");
        if (role === "bot") {
            bubble.classList.add("chatbot-markdown");
            bubble.innerHTML = renderMarkdown(text);
        } else {
            bubble.textContent = text;
        }

        const label = document.createElement("p");
        label.className =
            "text-[10px] text-slate-600 mt-1 " +
            (role === "user" ? "mr-1" : "ml-1");
        label.textContent = role === "user" ? "Bạn" : "VNTech AI";

        wrapper.appendChild(bubble);
        wrapper.appendChild(label);
        messages.appendChild(wrapper);
        scrollBottom();
        return wrapper;
    }

    function renderMarkdown(text) {
        return DOMPurify.sanitize(marked.parse(text ?? ""), {
            USE_PROFILES: { html: true },
            ALLOWED_ATTR: ["href", "title", "target", "rel"],
        });
    }

    function addTyping() {
        const wrapper = document.createElement("div");
        wrapper.className = "self-start max-w-[82%] chat-msg-in";
        wrapper.innerHTML = `
            <div class="bg-white/5 border border-white/10 rounded-2xl rounded-tl-sm px-5 py-3.5 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-lime-400" style="animation:typing-bounce 1.4s infinite;animation-delay:0s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-lime-400" style="animation:typing-bounce 1.4s infinite;animation-delay:.2s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-lime-400" style="animation:typing-bounce 1.4s infinite;animation-delay:.4s"></span>
            </div>`;
        messages.appendChild(wrapper);
        scrollBottom();
        return wrapper;
    }

    function setLoading(on) {
        inputField.disabled = on;
        sendBtn.disabled = on;
    }

    function scrollBottom() {
        messages.scrollTop = messages.scrollHeight;
    }
});
