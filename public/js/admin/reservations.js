//function to handle the view details modal for reservations
document.querySelectorAll('[data-bs-target="#viewReservationModal"]').forEach(btn => {
    btn.addEventListener('click', function () {
        viewReservation(
            this.dataset.id,
            this.dataset.title,
            this.dataset.content
        );
    });
});

function viewReservation(id, title, content) {
    const titleEl = document.getElementById('viewTitle');
    const contentEl = document.getElementById('viewContent');
    titleEl.textContent = title;
    contentEl.textContent = content;
}