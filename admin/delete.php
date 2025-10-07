<!--Cute si Andrei Gyles Lim-->

<?php
require __DIR__.'/db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id){ header('Location: view.php'); exit; }

// Fetch row for display
$stmt = $sql->prepare("SELECT * FROM employeedetails WHERE DataEntryID=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row){ header('Location: view.php'); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt2 = $sql->prepare("DELETE FROM employeedetails WHERE DataEntryID=?");
  $stmt2->bind_param("i",$id);
  if ($stmt2->execute()){
    header('Location: view.php'); exit;
  }
  $err = "Delete failed: ".htmlspecialchars($stmt2->error);
}

include __DIR__.'/partials/header.php';
include __DIR__.'/partials/sidebar.php';
include __DIR__.'/partials/topbar.php';
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-7 mx-auto">
      <div class="card border-danger">
        <div class="card-header border-danger">
          <h5 class="mb-0 text-danger">Delete Record #<?php echo $id; ?>?</h5>
        </div>
        <div class="card-body">
          <?php if (!empty($err)): ?><div class="alert alert-danger"><?php echo $err; ?></div><?php endif; ?>
          <p class="mb-3">You are about to delete the following entry:</p>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between"><span>Name</span><strong><?php 
              echo htmlspecialchars($row['LastName'].", ".$row['FirstName']); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Date</span><strong><?php echo htmlspecialchars($row['ShiftDate']); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Shift No</span><strong><?php echo (int)$row['ShiftNo']; ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Duty</span><strong><?php echo htmlspecialchars($row['DutyType']); ?></strong></li>
          </ul>
          <form method="post" class="d-flex gap-2">
            <button class="btn btn-danger"><i class='bx bx-trash me-1'></i>Confirm Delete</button>
            <a class="btn btn-outline-secondary" href="view.php">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__.'/partials/footer.php'; ?>
