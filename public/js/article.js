document.addEventListener('DOMContentLoaded', () => {
    // Fade-in animation for cards
    const cards = document.querySelectorAll('.article-card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '20px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100); // Staggered effect
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease-out';
        observer.observe(card);
    });

    // Confirmation for toggling status
    const toggleButtons = document.querySelectorAll('form[action*="toggle"] button');
    toggleButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm('Voulez-vous vraiment changer l\'état de cet article ?')) {
                e.preventDefault();
            }
        });
    });
});
