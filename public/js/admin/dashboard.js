(function () {
  "use strict";

  /* ── Date display ── */
  const now = new Date();
  document.getElementById('dash-date').textContent =
    now.toLocaleDateString('en-PH', { weekday:'long', year:'numeric', month:'long', day:'numeric' });

  /* ── Helpers ── */
  function formatCurrency(val) { return '₱' + parseFloat(val).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}); }
  function formatDate(dateStr) { return new Date(dateStr).toLocaleDateString('en-PH', {month:'short', day:'numeric'}); }
  function getStatusPill(status) {
    const pills = {
      'pending': 'pill-amber',
      'confirmed': 'pill-green',
      'cancelled': 'pill-red',
      'completed': 'pill-blue',
      'checked-in': 'pill-green',
      'reserved': 'pill-blue',
      'checked-out': 'pill-gray',
      'no-show': 'pill-red'
    };
    return pills[status] || 'pill-blue';
  }

  let active = 'today';
  let revInst, bookInst;

  /* ── Fetch Dashboard Stats ── */
  async function fetchDashboardStats(period = 'today') {
    try {
      const response = await fetch(`/api/dashboard-stats.php?period=${period}`);
      if (!response.ok) throw new Error('Failed to fetch dashboard data');
      
      const json = await response.json();
      if (!json.success) throw new Error(json.message);
      
      return json.data;
    } catch (error) {
      console.error('Error fetching dashboard stats:', error);
      return null;
    }
  }

  /* ── Update Dashboard ── */
  async function updateDashboard(period = 'today') {
    const stats = await fetchDashboardStats(period);
    if (!stats) return;

    // Update KPIs
    const occupancy = stats.occupancy;
    const revenue = stats.revenue.total;
    document.getElementById('kpi-occ').textContent = occupancy.percent + '%';
    document.getElementById('kpi-rev').textContent = formatCurrency(revenue);
    document.getElementById('kpi-events').textContent = stats.events.length;
    document.getElementById('kpi-checkin').textContent = stats.today_checkins;
    document.getElementById('kpi-fnb').textContent = '₱0';
    document.getElementById('kpi-sat').textContent = '4.7';

    // Update room occupancy ring
    updateOccupancyRing(occupancy);

    // Update revenue chart
    updateRevenueChart(stats.revenue.daily);

    // Update check-in/check-out chart
    updateBookingChart(stats.check_in_trend, stats.check_out_trend);

    // Update room type progress bars
    updateRoomTypeProgress(stats.room_types);

    // Update upcoming events
    updateEventsList(stats.events);

    // Update recent bookings
    updateBookingsList(stats.bookings);
  }

  /* ── Occupancy Ring ── */
  function updateOccupancyRing(occupancy) {
    const ctx = document.getElementById('occRing');
    if (!ctx) return;
    
    if (ctx.chart instanceof Chart) { ctx.chart.destroy(); }

    ctx.chart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [occupancy.percent, 100 - occupancy.percent - 5, 5],
          backgroundColor: ['#3266ad', '#f0f0f2', '#E89C3F'],
          borderWidth: 0, hoverOffset: 4
        }]
      },
      options: {
        responsive: false, cutout: '72%',
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => { const labels = [' Occupied', ' Vacant', ' Maintenance']; return labels[ctx.dataIndex] + ': ' + ctx.raw + '%'; } } }
        }
      }
    });
  }

  /* ── Revenue Chart ── */
  function updateRevenueChart(dailyData) {
    const labels = dailyData.map(d => formatDate(d.date));
    const roomsData = dailyData.map(d => d.rooms_revenue);
    const eventsData = dailyData.map(d => d.events_revenue);
    const fnbData = dailyData.map(d => d.fnb_revenue);

    if (!revInst) {
      revInst = new Chart(document.getElementById('revChart'), {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [
            { label: 'Rooms',  data: roomsData,  backgroundColor: 'rgba(50,102,173,0.75)', borderRadius: { topLeft:3, topRight:3 }, borderSkipped: false },
            { label: 'Events', data: eventsData, backgroundColor: 'rgba(155,126,212,0.75)' },
            { label: 'F&B',    data: fnbData,    backgroundColor: 'rgba(232,156,63,0.75)'  }
          ]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: { padding: 10, cornerRadius: 8, callbacks: { label: ctx => ' ' + formatCurrency(ctx.raw) } }
          },
          scales: {
            x: { stacked: true, grid: { display: false }, ticks: { font: { size: 11 }, color: '#aaa' } },
            y: { stacked: true, grid: { color: 'rgba(0,0,0,.05)' }, ticks: { font: { size: 11 }, color: '#aaa', callback: v => '₱' + (v/1000).toFixed(0) + 'k' } }
          }
        }
      });
    } else {
      revInst.data.labels = labels;
      revInst.data.datasets[0].data = roomsData;
      revInst.data.datasets[1].data = eventsData;
      revInst.data.datasets[2].data = fnbData;
      revInst.update('active');
    }
  }

  /* ── Booking Chart ── */
  function updateBookingChart(checkInData, checkOutData) {
    const last7Days = Array.from({length: 7}, (_, i) => {
      const d = new Date();
      d.setDate(d.getDate() - (6 - i));
      return d.toISOString().split('T')[0];
    });

    const labels = last7Days.map(d => formatDate(d));
    const checkInMap = Object.fromEntries(checkInData.map(d => [d.date, d.check_ins]));
    const checkOutMap = Object.fromEntries(checkOutData.map(d => [d.date, d.check_outs]));

    const checkInValues = last7Days.map(d => checkInMap[d] || 0);
    const checkOutValues = last7Days.map(d => checkOutMap[d] || 0);

    if (!bookInst) {
      bookInst = new Chart(document.getElementById('bookChart'), {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            { label: 'Check-ins', data: checkInValues, borderColor: '#3266ad', backgroundColor: 'rgba(50,102,173,0.08)', borderWidth: 2, pointRadius: 4, pointBackgroundColor: '#3266ad', fill: true, tension: 0.4 },
            { label: 'Check-outs', data: checkOutValues, borderColor: '#E89C3F', backgroundColor: 'rgba(232,156,63,0.08)', borderWidth: 2, pointRadius: 4, pointBackgroundColor: '#E89C3F', fill: true, tension: 0.4, borderDash: [5, 3] }
          ]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: { legend: { display: false }, tooltip: { padding: 10, cornerRadius: 8 } },
          scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#aaa' } },
            y: { grid: { color: 'rgba(0,0,0,.05)' }, ticks: { font: { size: 11 }, color: '#aaa', stepSize: 5 } }
          }
        }
      });
    } else {
      bookInst.data.labels = labels;
      bookInst.data.datasets[0].data = checkInValues;
      bookInst.data.datasets[1].data = checkOutValues;
      bookInst.update('active');
    }
  }

  /* ── Room Type Progress ── */
  function updateRoomTypeProgress(roomTypes) {
    const rp = document.getElementById('room-prog');
    rp.innerHTML = '';
    const colors = ['#3266ad', '#9B7ED4', '#1D9E75', '#E89C3F', '#E05151'];
    roomTypes.forEach((r, i) => {
      rp.innerHTML += `<div class="prog-bar-wrap"><div class="prog-bar-label"><span>${r.title}</span><span style="font-weight:500;">${r.occupancy_percent}%</span></div><div class="prog-track"><div class="prog-fill" style="width:${r.occupancy_percent}%;background:${colors[i % colors.length]};"></div></div></div>`;
    });
  }

  /* ── Events List ── */
  function updateEventsList(events) {
    const el = document.getElementById('events-list');
    el.innerHTML = '';
    events.forEach(e => {
      const date = new Date(e.event_date);
      const day = date.getDate();
      const mon = date.toLocaleString('en-US', {month: 'short'}).toUpperCase();
      const pill = e.status === 'upcoming' ? 'pill-blue' : 'pill-amber';
      el.innerHTML += `<div class="event-item"><div class="event-date-badge"><div class="event-date-day">${day}</div><div class="event-date-mon">${mon}</div></div><div style="flex:1;min-width:0;"><p class="event-info-name">${e.title}</p><p class="event-info-meta">${e.location || 'TBD'} &middot; ${e.capacity || 0} guests</p></div><span class="pill ${pill}">${e.status}</span></div>`;
    });
  }

  /* ── Recent Bookings ── */
  function updateBookingsList(bookings) {
    const avatarColors = [{bg:'#EEEDFE',tx:'#3C3489'},{bg:'#E1F5EE',tx:'#085041'},{bg:'#FAEEDA',tx:'#633806'},{bg:'#E6F1FB',tx:'#0C447C'},{bg:'#FAECE7',tx:'#712B13'},{bg:'#EAF3DE',tx:'#27500A'}];
    const bb = document.getElementById('bookings-body');
    bb.innerHTML = '';
    bookings.slice(0, 6).forEach((b, i) => {
      const ini = b.full_name.split(' ').map(n => n[0]).join('').slice(0, 2);
      const c = avatarColors[i % avatarColors.length];
      const pill = getStatusPill(b.status);
      bb.innerHTML += `<tr><td><span class="ava" style="background:${c.bg};color:${c.tx};">${ini}</span>${b.full_name}</td><td style="color:#888;">Room ${b.room_number}</td><td><span class="pill ${pill}" style="text-transform:capitalize;">${b.status}</span></td></tr>`;
    });
  }

  /* ── Period Switch ── */
  window.setPeriod = function (el, p) {
    document.querySelectorAll('.period-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    updateDashboard(p);
  };

  /* ── Initialize ── */
  updateDashboard('today');

  /* ── Event Type Doughnut ── */
  new Chart(document.getElementById('evtChart'), {
    type: 'doughnut',
    data: {
      labels: ['Wedding','Corporate','Birthday','Others'],
      datasets: [{
        data: [40, 30, 18, 12],
        backgroundColor: ['#9B7ED4','#3266ad','#E89C3F','#1D9E75'],
        borderWidth: 0, hoverOffset: 6
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '65%',
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.raw + '%' } } }
    }
  });

})();
