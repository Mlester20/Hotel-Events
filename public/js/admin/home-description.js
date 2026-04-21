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

//function to show the full content in a modal
document.querySelectorAll('[data-bs-target="#viewHomeDescriptionModal"]').forEach(btn => {
    btn.addEventListener('click', function () {
        viewHomeDescription(
            this.dataset.id,
            this.dataset.title,
            this.dataset.content
        );
    });
});

function viewHomeDescription(id, title, content) {
    const titleEl = document.getElementById('viewTitle');
    const contentEl = document.getElementById('viewContent');
    titleEl.textContent = title;
    contentEl.textContent = content;
}