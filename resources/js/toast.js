window.showToast = function (message, type = 'success', duration = 3000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const colors = {
        success: 'bg-green-600',
        error: 'bg-red-600',
        info: 'bg-blue-600',
        warning: 'bg-yellow-400 text-black'
    };

    const toast = document.createElement('div');
    toast.className = `
        ${colors[type] || colors.success}
        text-white px-4 py-3 rounded-xl shadow-lg
        text-sm font-semibold
        transform transition-all duration-300
        translate-x-full opacity-0
        pointer-events-auto
        max-w-xs w-full
    `;

    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <span class="flex-1">${message}</span>
            <button class="font-bold">×</button>
        </div>
    `;

    container.appendChild(toast);

    // animasi masuk
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    });

    // close manual
    toast.querySelector('button').onclick = close;

    // auto close
    const close = () => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    };

    setTimeout(close, duration);
};
