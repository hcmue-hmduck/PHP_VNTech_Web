function initHomePage() {
    console.log('home.js loaded');

    // Lucide icons
    if (window.lucide) {
        window.lucide.createIcons();
    }

    // Category pagination
    const perPage = 12;

    document.querySelectorAll('.category-section').forEach(function (section) {
        const items = Array.from(section.querySelectorAll('.product-item'));
        const pagination = section.querySelector('.pagination');

        if (!pagination || items.length === 0) return;

        const totalPages = Math.ceil(items.length / perPage);
        let currentPage = 1;

        function renderPage(page) {
            currentPage = page;

            items.forEach(function (item, index) {
                const start = (page - 1) * perPage;
                const end = start + perPage;

                if (index >= start && index < end) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });

            renderPagination();
        }

        function renderPagination() {
            pagination.innerHTML = '';

            if (totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');

                btn.type = 'button';
                btn.textContent = i;

                btn.className = i === currentPage
                    ? 'w-10 h-10 rounded-xl border text-xs font-black transition-all bg-brand-500 text-white border-brand-500 shadow-[0_4px_12px_rgba(255,79,0,0.15)]'
                    : 'w-10 h-10 rounded-xl border text-xs font-black transition-all bg-white/5 text-slate-400 border-white/10 hover:border-brand-500 hover:text-brand-500';

                btn.addEventListener('click', function () {
                    renderPage(i);

                    section.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });

                pagination.appendChild(btn);
            }
        }

        renderPage(1);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHomePage);
} else {
    initHomePage();
}

window.timer = function (targetTime) {
    return {
        hours: '00',
        minutes: '00',
        seconds: '00',
        ms: '00',
        target: targetTime ? new Date(targetTime).getTime() : null,

        init() {
            if (!this.target) {
                this.target = new Date().getTime() + 2 * 3600 * 1000 + 45 * 60 * 1000;
            }
            setInterval(() => {
                const now = new Date().getTime();
                const diff = this.target - now;

                if (diff <= 0) {
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    this.ms = '00';
                    return;
                }

                const h = Math.floor(diff / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);
                const milliseconds = Math.floor((diff % 1000) / 10);

                this.hours = h.toString().padStart(2, '0');
                this.minutes = m.toString().padStart(2, '0');
                this.seconds = s.toString().padStart(2, '0');
                this.ms = milliseconds.toString().padStart(2, '0');
            }, 10);
        }
    };
};