import './bootstrap';

const progress = document.querySelector('[data-read-progress]');

if (progress instanceof HTMLElement) {
    const update = () => {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.width = `${max > 0 ? (window.scrollY / max) * 100 : 0}%`;
    };

    window.addEventListener('scroll', update, { passive: true });
    update();
}

document.querySelector('[data-copy-url]')?.addEventListener('click', async (event) => {
    const button = event.currentTarget;

    if (!(button instanceof HTMLElement)) {
        return;
    }

    try {
        await navigator.clipboard.writeText(window.location.href);
        button.dataset.copied = 'true';
        window.setTimeout(() => {
            delete button.dataset.copied;
        }, 2000);
    } catch {
        // Browser blocked clipboard access; the visible URL still works.
    }
});
