<?php
// menu.php
require __DIR__.'/db.php';

// Quick stats
$totalRow    = $sql->query("SELECT COUNT(*) AS c FROM employeedetails");
$total       = $totalRow ? (int)$totalRow->fetch_assoc()['c'] : 0;

$ondutyRow   = $sql->query("SELECT COUNT(*) AS c FROM employeedetails WHERE DutyType='OnDuty'");
$onduty      = $ondutyRow ? (int)$ondutyRow->fetch_assoc()['c'] : 0;

$lateRow     = $sql->query("SELECT COUNT(*) AS c FROM employeedetails WHERE DutyType='Late'");
$late        = $lateRow ? (int)$lateRow->fetch_assoc()['c'] : 0;

$overtimeRow = $sql->query("SELECT COUNT(*) AS c FROM employeedetails WHERE DutyType='Overtime'");
$overtime    = $overtimeRow ? (int)$overtimeRow->fetch_assoc()['c'] : 0;

include __DIR__.'/partials/header.php';
include __DIR__.'/partials/sidebar.php';
include __DIR__.'/partials/topbar.php';
?>
<div class="app-content container-fluid">

  <!-- Welcome banner -->
  <div class="row mb-3">
    <div class="col-12">
      <div class="card shadow-soft" style="border-left:6px solid var(--sneat-primary, var(--bs-primary));">
        <div class="card-body d-flex justify-content-between align-items-center" style="padding:16px 18px;">
          <div>
            <div style="font-weight:800; font-size:1.05rem; color:var(--sneat-primary, var(--bs-primary))">Welcome, Admin!</div>
            <div class="text-muted-2">Use this dashboard to browse, add, and manage employee duty records.</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick stats  -->
  <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 align-items-stretch">

    <!-- Total Records -->
    <div class="col d-flex">
      <div
        class="card shadow-soft border-0 h-100 w-100"
        style="min-height: 150px; border-left:6px solid var(--sneat-primary, var(--bs-primary));"
      >
        <div class="card-body d-flex align-items-center justify-content-between" style="padding: 20px 22px;">
          <div>
            <div class="text-muted">Total Records</div>
            <div class="h3 mb-0 text-primary"><?= $total ?></div>
          </div>
          <i class='bx bx-collection text-primary' style="font-size: 2.25rem;"></i>
        </div>
      </div>
    </div>

    <!-- On Duty -->
    <div class="col d-flex">
      <div
        class="card shadow-soft border-0 h-100 w-100"
        style="min-height: 150px; border-left:6px solid var(--sneat-success, var(--bs-success));"
      >
        <div class="card-body d-flex align-items-center justify-content-between" style="padding: 20px 22px;">
          <div>
            <div class="text-muted">On Duty</div>
            <div class="h3 mb-0 text-success"><?= $onduty ?></div>
          </div>
          <i class='bx bx-check-circle text-success' style="font-size: 2.25rem;"></i>
        </div>
      </div>
    </div>

    <!-- Late -->
    <div class="col d-flex">
      <div
        class="card shadow-soft border-0 h-100 w-100"
        style="min-height: 150px; border-left:6px solid var(--sneat-warning, var(--bs-warning));"
      >
        <div class="card-body d-flex align-items-center justify-content-between" style="padding: 20px 22px;">
          <div>
            <div class="text-muted">Late</div>
            <div class="h3 mb-0 text-warning"><?= $late ?></div>
          </div>
          <i class='bx bx-time-five text-warning' style="font-size: 2.25rem;"></i>
        </div>
      </div>
    </div>

    <!-- Overtime  -->
    <div class="col d-flex">
      <div
        class="card shadow-soft border-0 h-100 w-100"
        style="min-height: 150px; border-left:6px solid var(--sneat-danger, var(--bs-danger));"
      >
        <div class="card-body d-flex align-items-center justify-content-between" style="padding: 20px 22px;">
          <div>
            <div class="text-muted">Overtime</div>
            <div class="h3 mb-0 text-danger"><?= (int)$overtime ?></div>
          </div>
          <i class='bx bx-plus-circle text-danger' style="font-size: 2.25rem;"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Links -->
  <div class="row mt-3">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Quick Links</h5>
        </div>
        <div class="card-body">
          <a href="view.php" class="btn btn-primary me-2">
            <i class='bx bx-table me-1'></i>Browse Records
          </a>
          <a href="edit.php" class="btn btn-outline-primary">
            <i class='bx bx-plus me-1'></i>Add New Record
          </a>
        </div>
      </div>
    </div>
  </div>

</div>
<?php include __DIR__.'/partials/footer.php'; ?>
