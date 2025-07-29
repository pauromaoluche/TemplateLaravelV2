document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal:message', (event) => {
        const data = event[0];
        Swal.fire({
            title: data.title || 'Atenção!',
            text: data.text || '',
            icon: data.icon || 'info',
            confirmButtonText: data.confirmButtonText || 'Ok'
        }).then((result) => {
            if (data.redirect && result.isConfirmed) {
                window.location.href = data.redirect;
            }
            if (data.reload && result.isConfirmed) {
                window.location.reload();
            }
        });
    });

    Livewire.on('swal:confirm', (event) => {
        const data = event[0];
        Swal.fire({
            title: data.title || 'Tem certeza?',
            text: data.text || 'Você não poderá reverter isso!',
            icon: data.icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: data.confirmButtonColor || '#3085d6',
            cancelButtonColor: data.cancelButtonColor || '#d33',
            confirmButtonText: data.confirmButtonText || 'Sim, continuar!',
            cancelButtonText: data.cancelButtonText || 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch(data.onConfirmedEvent, [data.onConfirmedParams] || {});
            }
        });
    });
});
