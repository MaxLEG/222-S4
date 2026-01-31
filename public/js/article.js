document.addEventListener('DOMContentLoaded', () => {
    const toggleButtons = document.querySelectorAll('form[action*="toggle"] button');
    toggleButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm('Voulez-vous vraiment changer l\'état de cet article ?')) {
                e.preventDefault();
            }
        });
    });
});
