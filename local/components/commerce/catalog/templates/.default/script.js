document.addEventListener('DOMContentLoaded', () => {
    const catalog = document.querySelector('[data-catalog]');
    if (!catalog) return;

    const form = catalog.querySelector('[data-filter-form]');

    form?.addEventListener('submit', (event) => {
        event.preventDefault();

        const params = new URLSearchParams(new FormData(form));
        const url = `${window.location.pathname}?${params.toString()}`;

        window.history.pushState({}, '', url);
        window.location.reload();
    });

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-add-to-cart]');
        if (!button) return;

        const body = new URLSearchParams({
            sessid: window.BX?.bitrix_sessid?.() || '',
            action: 'add',
            product_id: button.dataset.productId,
            quantity: '1'
        });

        const response = await fetch('/local/ajax/basket.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body
        });

        const result = await response.json();

        if (!result.success) {
            alert(result.error || 'Не удалось добавить товар.');
            return;
        }

        button.textContent = 'Добавлено';
    });
});
