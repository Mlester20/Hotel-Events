document.querySelectorAll('[data-bs-target="#editHomeDescriptionModal"]').forEach(btn => {
    btn.addEventListener('click', function () {
        editHomeDescription(
            this.dataset.id,
            this.dataset.title,
            this.dataset.content
        );
    });
});

function editHomeDescription(id, title, content) {
    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editContent').value = content;
}