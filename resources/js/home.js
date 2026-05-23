function initHomePage() {
    console.log('home.js loaded');

    // Lucide icons
    if (window.lucide) {
        window.lucide.createIcons();
    }

    // Category pagination
    const perPage = 6;

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
                    ? 'w-10 h-10 rounded-xl border text-xs font-black transition-all bg-lime-400 text-black border-lime-400'
                    : 'w-10 h-10 rounded-xl border text-xs font-black transition-all bg-white/5 text-slate-400 border-white/10 hover:border-lime-400 hover:text-lime-400';

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

// Alpine Timer
window.timer = function () {
    return {
        timeLeft: {
            Hours: '02',
            Mins: '45',
            Secs: '12'
        },

        init() {
            setInterval(() => {
                let h = parseInt(this.timeLeft.Hours);
                let m = parseInt(this.timeLeft.Mins);
                let s = parseInt(this.timeLeft.Secs);

                s--;

                if (s < 0) {
                    s = 59;
                    m--;
                }

                if (m < 0) {
                    m = 59;
                    h--;
                }

                if (h < 0) {
                    h = 0;
                    m = 0;
                    s = 0;
                }

                this.timeLeft.Hours = h.toString().padStart(2, '0');
                this.timeLeft.Mins = m.toString().padStart(2, '0');
                this.timeLeft.Secs = s.toString().padStart(2, '0');
            }, 1000);
        }
    };
};