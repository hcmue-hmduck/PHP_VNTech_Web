// Initialize Lucide Icons
if (window.lucide) {
    lucide.createIcons();
}

// Alpine.js Timer Component
window.timer = function() {
    return {
        timeLeft: { Hours: '02', Mins: '45', Secs: '12' },
        init() {
            setInterval(() => {
                let h = parseInt(this.timeLeft.Hours);
                let m = parseInt(this.timeLeft.Mins);
                let s = parseInt(this.timeLeft.Secs);
                s--;
                if (s < 0) { s = 59; m--; }
                if (m < 0) { m = 59; h--; }
                if (h < 0) h = 0;
                this.timeLeft.Hours = h.toString().padStart(2, '0');
                this.timeLeft.Mins = m.toString().padStart(2, '0');
                this.timeLeft.Secs = s.toString().padStart(2, '0');
            }, 1000);
        }
    }
}
