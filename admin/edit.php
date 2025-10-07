<?php

require __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$row = [
  'LastName'  => '',
  'FirstName' => '',
  'ShiftDate' => date('Y-m-d'),
  'ShiftNo'   => 1,
  'Hours'     => 8,
  'DutyType'  => 'OnDuty'
];

$errors = [];
$saved  = false;

// Handle saves
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $LastName  = trim($_POST['LastName']  ?? '');
  $FirstName = trim($_POST['FirstName'] ?? '');
  $ShiftDate = trim($_POST['ShiftDate'] ?? '');
  $ShiftNo   = (int)($_POST['ShiftNo']  ?? 1);
  $Hours     = (int)($_POST['Hours']    ?? 8);
  $DutyType  = trim($_POST['DutyType']  ?? 'OnDuty');

  if ($LastName === '')  { $errors[] = "Last name is required."; }
  if ($FirstName === '') { $errors[] = "First name is required."; }
  if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $ShiftDate)) { $errors[] = "Shift date must be YYYY-MM-DD."; }
  if ($ShiftNo < 1 || $ShiftNo > 3) { $errors[] = "Shift number must be 1, 2, or 3."; }
  if ($Hours < 0 || $Hours > 24) { $errors[] = "Hours must be between 0 and 24."; }
  $allowedDuty = ['OnDuty','Late','Overtime'];
  if (!in_array($DutyType, $allowedDuty, true)) { $errors[] = "Duty type is invalid."; }

  if (!$errors) {
    if ($id) {
      // UPDATE — 7 placeholders (sssiisi)
      $stmt = $sql->prepare("UPDATE employeedetails
                               SET LastName=?, FirstName=?, ShiftDate=?, ShiftNo=?, Hours=?, DutyType=?
                             WHERE DataEntryID=?");
      if (!$stmt) {
        $errors[] = "Prepare failed: " . htmlspecialchars($sql->error);
      } else {
        // types: s = string, i = integer
        $stmt->bind_param('sssiisi', $LastName, $FirstName, $ShiftDate, $ShiftNo, $Hours, $DutyType, $id);
        if ($stmt->execute()) {
          $saved = true;
        } else {
          $errors[] = "Update failed: " . htmlspecialchars($stmt->error);
        }
      }
    } else {
      // INSERT — 6 placeholders (sssiis)
      $stmt = $sql->prepare("INSERT INTO employeedetails (LastName, FirstName, ShiftDate, ShiftNo, Hours, DutyType)
                             VALUES (?,?,?,?,?,?)");
      if (!$stmt) {
        $errors[] = "Prepare failed: " . htmlspecialchars($sql->error);
      } else {
        $stmt->bind_param('sssiis', $LastName, $FirstName, $ShiftDate, $ShiftNo, $Hours, $DutyType);
        if ($stmt->execute()) {
          $id = $stmt->insert_id;
          $saved = true;
        } else {
          $errors[] = "Insert failed: " . htmlspecialchars($stmt->error);
        }
      }
    }
  }

  // Refresh row after successful save
  if ($saved && $id) {
    $stmt2 = $sql->prepare("SELECT * FROM employeedetails WHERE DataEntryID=?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $row = $stmt2->get_result()->fetch_assoc();
  } else {
    $row = [
      'LastName'  => $LastName,
      'FirstName' => $FirstName,
      'ShiftDate' => $ShiftDate,
      'ShiftNo'   => $ShiftNo,
      'Hours'     => $Hours,
      'DutyType'  => $DutyType
    ];
  }
} else {
  // Initial load for edit
  if ($id) {
    $stmt = $sql->prepare("SELECT * FROM employeedetails WHERE DataEntryID=?");
    if (!$stmt) { die("Prepare failed: " . htmlspecialchars($sql->error)); }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows) {
      $row = $res->fetch_assoc();
    } else {
      header("Location: view.php");
      exit;
    }
  }
}

include __DIR__.'/partials/header.php';
include __DIR__.'/partials/sidebar.php';
include __DIR__.'/partials/topbar.php';
?>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-xl-8 col-lg-9 mx-auto">
      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php elseif ($saved): ?>
        <div class="alert alert-success">Record saved.</div>
      <?php endif; ?>

      <div class="card shadow-soft" style="border-radius: 1.2rem;">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><?= $id ? 'Edit Record #'.(int)$id : 'Add New Record' ?></h5>
          <div>
            <?php if ($id): ?>
              <a href="delete.php?id=<?= (int)$id ?>" class="btn btn-sm btn-outline-danger">
                <i class='bx bx-trash me-1'></i> Delete
              </a>
            <?php endif; ?>
            <a href="view.php" class="btn btn-sm btn-outline-secondary">Back</a>
          </div>
        </div>
        <div class="card-body">
          <form method="post" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Last Name</label>
              <input type="text" name="LastName" class="form-control" value="<?= htmlspecialchars($row['LastName']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">First Name</label>
              <input type="text" name="FirstName" class="form-control" value="<?= htmlspecialchars($row['FirstName']) ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Shift Date</label>
              <input type="date" name="ShiftDate" class="form-control" value="<?= htmlspecialchars($row['ShiftDate']) ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Shift No</label>
              <select name="ShiftNo" class="form-select" required>
                <?php foreach ([1,2,3] as $s): ?>
                  <option value="<?= $s ?>" <?= ((int)$row['ShiftNo'] === $s) ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Hours</label>
              <input type="number" min="0" max="24" name="Hours" class="form-control" value="<?= (int)$row['Hours'] ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Duty Type</label>
              <select name="DutyType" class="form-select" required>
                <?php foreach (['OnDuty','Late','Overtime'] as $d): ?>
                  <option value="<?= $d ?>" <?= ($row['DutyType'] === $d) ? 'selected' : '' ?>><?= $d ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <button class="btn btn-primary">
                <i class='bx bx-save me-1'></i> Save
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
