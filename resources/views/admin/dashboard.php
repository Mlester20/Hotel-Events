<?php
session_start();

require_once __DIR__ . '/../../../middleware/auth.php';
allowOnly(['admin']);
?>

<!doctype html>
<html lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="../../../public/assets/"
  data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title><?php require_once __DIR__ . '/../../../helpers/title.php'; ?></title>
  <link rel="icon" type="image/x-icon" href="../../../public/assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../public/assets/vendor/fonts/iconify-icons.css" />
  <link rel="stylesheet" href="../../../public/assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="../../../public/assets/vendor/css/core.css" />
  <link rel="stylesheet" href="../../../public/assets/css/demo.css" />
  <link rel="stylesheet" href="../../../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../../../public/assets/vendor/libs/apex-charts/apex-charts.css" />
  <link rel="stylesheet" href="../../../public/css/admin-dashboard.css" />
  <script src="../../../public/assets/vendor/js/helpers.js"></script>
  <script src="../../../public/assets/js/config.js"></script>
</head>
<body>

  <?php require_once 'layout/sidebar.php'; ?>
  <?php require_once 'layout/topbar.php'; ?>


  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="dash-wrap">

        <!-- ── Header ── -->
        <div class="dash-header">
          <div class="dash-header-left">
            <h4>Hotel & Events Dashboard</h4>
            <p id="dash-date">Loading date...</p>
          </div>
          <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <span class="badge-live"><span class="dot-pulse"></span>Live data</span>
            <div class="period-tabs">
              <button class="period-tab active" onclick="setPeriod(this,'today')">Today</button>
              <button class="period-tab" onclick="setPeriod(this,'7d')">7 Days</button>
              <button class="period-tab" onclick="setPeriod(this,'30d')">30 Days</button>
            </div>
          </div>
        </div>

        <!-- ── KPI Row ── -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#E6F1FB;">🏨</div>
            <p class="kpi-label">Room Occupancy</p>
            <p class="kpi-value" id="kpi-occ">78%</p>
            <div class="kpi-change up">↑ +6% vs yesterday</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#E1F5EE;">₱</div>
            <p class="kpi-label">Total Revenue</p>
            <p class="kpi-value" id="kpi-rev">₱148,500</p>
            <div class="kpi-change up">↑ +11.2% vs last period</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#EEEDFE;">🎉</div>
            <p class="kpi-label">Active Events</p>
            <p class="kpi-value" id="kpi-events">5</p>
            <div class="kpi-change up">↑ 2 new this week</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#FAEEDA;">🛎️</div>
            <p class="kpi-label">Check-ins Today</p>
            <p class="kpi-value" id="kpi-checkin">24</p>
            <div class="kpi-change down">↓ -3 vs yesterday</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#FAECE7;">🍽️</div>
            <p class="kpi-label">F&B Revenue</p>
            <p class="kpi-value" id="kpi-fnb">₱38,200</p>
            <div class="kpi-change up">↑ +8.5% vs last period</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#EAF3DE;">⭐</div>
            <p class="kpi-label">Guest Satisfaction</p>
            <p class="kpi-value" id="kpi-sat">4.7</p>
            <div class="kpi-change up">↑ +0.2 this month</div>
          </div>
        </div>

        <!-- ── Row 1: Revenue trend + Occupancy ring ── -->
        <div class="chart-row cols-3-2">

          <div class="card-panel">
            <div class="card-head">
              <div>
                <p class="card-title">Revenue Breakdown</p>
                <p class="card-sub">Rooms · Events · F&B daily trend</p>
              </div>
              <div class="legend">
                <span class="leg-item"><span class="leg-dot" style="background:#3266ad;"></span>Rooms</span>
                <span class="leg-item"><span class="leg-dot" style="background:#9B7ED4;"></span>Events</span>
                <span class="leg-item"><span class="leg-dot" style="background:#E89C3F;"></span>F&B</span>
              </div>
            </div>
            <div style="position:relative;width:100%;height:230px;">
              <canvas id="revChart" role="img" aria-label="Stacked bar chart showing daily revenue from Rooms, Events, and F&B.">Revenue breakdown by category.</canvas>
            </div>
          </div>

          <div class="card-panel" style="display:flex;flex-direction:column;">
            <div class="card-head">
              <div>
                <p class="card-title">Room Occupancy</p>
                <p class="card-sub">Current status</p>
              </div>
            </div>
            <div class="occ-wrap" style="flex:1;">
              <div class="occ-ring">
                <canvas id="occRing" width="130" height="130" role="img" aria-label="Doughnut chart showing 78% room occupancy.">78% occupied.</canvas>
                <div class="occ-center">
                  <span class="occ-pct">78%</span>
                  <span class="occ-lbl">occupied</span>
                </div>
              </div>
              <div class="mini-stats" style="width:100%;">
                <div class="mini-stat">
                  <p class="mini-stat-val">62</p>
                  <p class="mini-stat-label">Occupied</p>
                </div>
                <div class="mini-stat">
                  <p class="mini-stat-val">17</p>
                  <p class="mini-stat-label">Vacant</p>
                </div>
                <div class="mini-stat">
                  <p class="mini-stat-val">5</p>
                  <p class="mini-stat-label">Maintenance</p>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- ── Row 2: Bookings trend + Event types ── -->
        <div class="chart-row cols-1-1">

          <div class="card-panel">
            <div class="card-head">
              <div>
                <p class="card-title">Room Bookings vs Check-outs</p>
                <p class="card-sub">Daily inflow & outflow</p>
              </div>
              <div class="legend">
                <span class="leg-item"><span class="leg-dot" style="background:#3266ad;border-radius:50%;"></span>Check-ins</span>
                <span class="leg-item"><span class="leg-dot" style="background:#E89C3F;border-radius:50%;"></span>Check-outs</span>
              </div>
            </div>
            <div style="position:relative;width:100%;height:210px;">
              <canvas id="bookChart" role="img" aria-label="Line chart comparing daily check-ins and check-outs.">Check-in and check-out trend.</canvas>
            </div>
          </div>

          <div class="card-panel">
            <div class="card-head">
              <div>
                <p class="card-title">Event Type Revenue</p>
                <p class="card-sub">Share by event category</p>
              </div>
            </div>
            <div style="position:relative;width:100%;height:160px;margin-bottom:10px;">
              <canvas id="evtChart" role="img" aria-label="Doughnut chart of event revenue by type: Wedding, Corporate, Birthday, Others.">Wedding 40%, Corporate 30%, Birthday 18%, Others 12%.</canvas>
            </div>
            <div class="legend" style="justify-content:center;">
              <span class="leg-item"><span class="leg-dot" style="background:#9B7ED4;"></span>Wedding 40%</span>
              <span class="leg-item"><span class="leg-dot" style="background:#3266ad;"></span>Corporate 30%</span>
              <span class="leg-item"><span class="leg-dot" style="background:#E89C3F;"></span>Birthday 18%</span>
              <span class="leg-item"><span class="leg-dot" style="background:#1D9E75;"></span>Others 12%</span>
            </div>
          </div>

        </div>

        <!-- ── Row 3: Room type performance + Upcoming events + Recent bookings ── -->
        <div class="chart-row cols-3">

          <div class="card-panel">
            <div class="card-head">
              <div>
                <p class="card-title">Room Type Performance</p>
                <p class="card-sub">Occupancy by category</p>
              </div>
            </div>
            <div id="room-prog" style="margin-top: .25rem;"></div>
          </div>

          <div class="card-panel">
            <div class="card-head">
              <div>
                <p class="card-title">Upcoming Events</p>
                <p class="card-sub">Next 5 scheduled events</p>
              </div>
            </div>
            <div id="events-list"></div>
          </div>

          <div class="card-panel">
            <div class="card-head">
              <div>
                <p class="card-title">Recent Bookings</p>
                <p class="card-sub">Latest reservations</p>
              </div>
            </div>
            <table class="dash-table">
              <thead>
                <tr><th>Guest</th><th>Room</th><th>Status</th></tr>
              </thead>
              <tbody id="bookings-body"></tbody>
            </table>
          </div>

        </div>

      </div><!-- /dash-wrap -->
    </div><!-- /container -->
  </div><!-- /content-wrapper -->

  <?php require_once 'layout/footer.php'; ?>

  <!-- ── Vendor scripts ── -->
  <script src="../../../public/assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../../../public/assets/vendor/libs/popper/popper.js"></script>
  <script src="../../../public/assets/vendor/js/bootstrap.js"></script>
  <script src="../../../public/assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="../../../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../../../public/assets/vendor/js/menu.js"></script>
  <script src="../../../public/assets/js/main.js"></script>

  <!-- ── Chart.js ── -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
  <script src="../../../public/js/admin/dashboard.js"></script>

</body>
</html>