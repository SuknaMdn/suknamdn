document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, content }) => {
            if (status === 419) {
                // Remove default popup behavior
                window.removeEventListener('beforeunload', Livewire.beforeWindowUnload)

                // Show custom alert
                alert('Your session has expired. Please refresh the page to continue.');

                // Optional: Automatically refresh the page
                window.location.reload();
            }
        });
    });
});
