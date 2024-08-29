document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.carte').forEach(card => {
        card.addEventListener('click', () => {
            card.classList.toggle('flipped');
        });
    });
});
