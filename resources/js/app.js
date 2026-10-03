// Make the logo available to Vite::asset() in Blade.
import.meta.glob(['../images/logo.jpeg'], { eager: true, query: '?url' });

// Map: the external iframe is only created after the visitor asks for it (DSGVO).
document.querySelectorAll('[data-map]').forEach((container) => {
    container.querySelector('[data-map-load]')?.addEventListener('click', () => {
        const iframe = document.createElement('iframe');

        iframe.src = container.dataset.src;
        iframe.title = container.dataset.title;
        iframe.loading = 'lazy';
        iframe.referrerPolicy = 'no-referrer';
        iframe.className = 'h-full w-full border-0';

        container.replaceChildren(iframe);
    });
});
