(() => {

  // ── Toast helper ──────────────────────────────────────────
  const toastBox = document.getElementById('toast-box');

  function toast(msg, ok = true) {
    const el = document.createElement('div');

    el.className = `toast-msg ${ok ? 'ok' : 'err'}`;
    el.innerHTML = `
      <i class="bi bi-${ok ? 'check-circle-fill' : 'x-circle-fill'}"
         style="color:var(--${ok ? 'green' : 'red'}); font-size:1rem;"></i>
      <span>${msg}</span>
    `;

    toastBox.appendChild(el);
    setTimeout(() => el.remove(), 3200);
  }

  // ── AJAX helper ───────────────────────────────────────────
  async function apiPost(action, booking_id, value) {
    const res = await fetch('../../../api/reservations.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action, booking_id, value }),
    });

    return res.json();
  }

  // ── Handle select changes ──────────────────────────────────
  document.querySelectorAll('.inline-select').forEach(sel => {

    sel.addEventListener('change', async function () {
      const row       = this.closest('tr');
      const bookingId = this.dataset.bookingId;
      const action    = this.dataset.action;
      const newVal    = this.value;
      const original  = this.dataset.original;

      // Optimistic update
      row.classList.add('saving');

      try {
        const json = await apiPost(action, bookingId, newVal);

        if (json.success) {
          this.dataset.original = newVal;
          toast(json.message, true);

          if (action === 'update_status') {
            row.dataset.status = newVal;
          }

        } else {
          this.value = original;
          toast(json.message || 'Update failed.', false);
        }

      } catch (err) {
        this.value = original;
        toast('Network error. Please try again.', false);

      } finally {
        row.classList.remove('saving');
      }
    });

  });

  // ── Filter buttons ────────────────────────────────────────
  const filterBtns = document.querySelectorAll('.filter-btn');
  const tbody = document.querySelector('#reservationsTable tbody');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      applyFilters();
    });
  });

  // ── Search ────────────────────────────────────────────────
  document
    .getElementById('searchInput')
    .addEventListener('input', applyFilters);

  function applyFilters() {
    const filterVal =
      document.querySelector('.filter-btn.active')?.dataset.filter ?? 'all';

    const search =
      document.getElementById('searchInput').value.toLowerCase().trim();

    tbody.querySelectorAll('tr[data-booking-id]').forEach(row => {
      const matchFilter =
        filterVal === 'all' || row.dataset.status === filterVal;

      const text = row.textContent.toLowerCase();
      const matchSearch =
        !search || text.includes(search);

      row.style.display =
        matchFilter && matchSearch ? '' : 'none';
    });
  }

})();