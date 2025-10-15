<?php
// employees.php
require __DIR__.'/db.php';

// Get filter parameters from query string
$selectedDept = isset($_GET['department']) ? $_GET['department'] : 'all';
$searchName = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
$filterRate = isset($_GET['rate_filter']) ? $_GET['rate_filter'] : 'all';

// Build WHERE clause
$whereConditions = [];
$params = [];
$types = '';

// Department filter
if ($selectedDept !== 'all') {
    $whereConditions[] = "Department = ?";
    $params[] = $selectedDept;
    $types .= 's';
}

// Name search
if (!empty($searchName)) {
    $whereConditions[] = "Name LIKE ?";
    $params[] = '%' . $searchName . '%';
    $types .= 's';
}

// Rate filter
if ($filterRate === 'high') {
    $whereConditions[] = "DailyRate >= 550";
} elseif ($filterRate === 'medium') {
    $whereConditions[] = "DailyRate BETWEEN 520 AND 549";
} elseif ($filterRate === 'low') {
    $whereConditions[] = "DailyRate < 520";
}

// Build query
$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Sorting
$orderBy = 'ORDER BY ';
switch ($sortBy) {
    case 'name_desc':
        $orderBy .= 'Name DESC';
        break;
    case 'dept_asc':
        $orderBy .= 'Department ASC, Name ASC';
        break;
    case 'dept_desc':
        $orderBy .= 'Department DESC, Name ASC';
        break;
    case 'rate_asc':
        $orderBy .= 'DailyRate ASC';
        break;
    case 'rate_desc':
        $orderBy .= 'DailyRate DESC';
        break;
    default: // name_asc
        $orderBy .= 'Name ASC';
}

$query = "SELECT * FROM employees $whereClause $orderBy";

// Execute query
if (!empty($params)) {
    $stmt = $sql->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $stmt = $sql->query($query);
    $employees = $stmt->fetch_all(MYSQLI_ASSOC);
}

// Get all departments for dropdown
$deptQuery = "SELECT DISTINCT Department FROM employees WHERE Department IS NOT NULL AND Department != '' ORDER BY Department";
$deptResult = $sql->query($deptQuery);
$departments = $deptResult->fetch_all(MYSQLI_ASSOC);

include __DIR__.'/partials/header.php';
include __DIR__.'/partials/sidebar.php';
include __DIR__.'/partials/topbar.php';
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="row mb-3">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1">Employee Directory</h4>
          <p class="text-muted-2 mb-0">Browse and manage all employee records</p>
        </div>
        <a href="edit.php" class="btn btn-primary">
          <i class='bx bx-plus me-1'></i> Add New Employee
        </a>
      </div>
    </div>
  </div>

  <!-- Filter Card -->
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="employees.php" id="filterForm" class="row g-3">
            
            <!-- Search by Name -->
            <div class="col-md-3">
              <label for="search" class="form-label fw-semibold">
                <i class='bx bx-search-alt me-1'></i> Search by Name
              </label>
              <input 
                type="text" 
                name="search" 
                id="search" 
                class="form-control" 
                placeholder="Enter employee name..."
                value="<?= htmlspecialchars($searchName) ?>"
                autocomplete="off"
                onkeyup="submitFormDelayed()">
            </div>

            <!-- Filter by Department -->
            <div class="col-md-2">
              <label for="department" class="form-label fw-semibold">
                <i class='bx bx-briefcase me-1'></i> Department
              </label>
              <select name="department" id="department" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= $selectedDept === 'all' ? 'selected' : '' ?>>All Departments</option>
                <?php foreach ($departments as $dept): ?>
                  <option value="<?= htmlspecialchars($dept['Department']) ?>" 
                          <?= $selectedDept === $dept['Department'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dept['Department']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Filter by Rate -->
            <div class="col-md-2">
              <label for="rate_filter" class="form-label fw-semibold">
                <i class='bx bx-dollar me-1'></i> Daily Rate
              </label>
              <select name="rate_filter" id="rate_filter" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= $filterRate === 'all' ? 'selected' : '' ?>>All Rates</option>
                <option value="high" <?= $filterRate === 'high' ? 'selected' : '' ?>>High (≥550)</option>
                <option value="medium" <?= $filterRate === 'medium' ? 'selected' : '' ?>>Medium (520-549)</option>
                <option value="low" <?= $filterRate === 'low' ? 'selected' : '' ?>>Low (<520)</option>
              </select>
            </div>

            <!-- Sort By -->
            <div class="col-md-2">
              <label for="sort" class="form-label fw-semibold">
                <i class='bx bx-sort me-1'></i> Sort By
              </label>
              <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                <option value="name_asc" <?= $sortBy === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                <option value="name_desc" <?= $sortBy === 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                <option value="dept_asc" <?= $sortBy === 'dept_asc' ? 'selected' : '' ?>>Department (A-Z)</option>
                <option value="dept_desc" <?= $sortBy === 'dept_desc' ? 'selected' : '' ?>>Department (Z-A)</option>
                <option value="rate_asc" <?= $sortBy === 'rate_asc' ? 'selected' : '' ?>>Rate (Low-High)</option>
                <option value="rate_desc" <?= $sortBy === 'rate_desc' ? 'selected' : '' ?>>Rate (High-Low)</option>
              </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-3 d-flex align-items-end gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                <i class='bx bx-filter-alt me-1'></i> Apply Filters
              </button>
              <?php if ($selectedDept !== 'all' || !empty($searchName) || $filterRate !== 'all' || $sortBy !== 'name_asc'): ?>
                <a href="employees.php" class="btn btn-outline-secondary" title="Clear all filters">
                  <i class='bx bx-x'></i>
                </a>
              <?php endif; ?>
            </div>

            <!-- Results Count -->
            <div class="col-12">
              <div class="d-flex align-items-center gap-2 text-muted-2">
                <i class='bx bx-info-circle'></i>
                <span>
                  Showing <strong><?= count($employees) ?></strong> employee(s)
                  <?php if ($selectedDept !== 'all'): ?>
                    in <strong><?= htmlspecialchars($selectedDept) ?></strong>
                  <?php endif; ?>
                  <?php if (!empty($searchName)): ?>
                    matching "<strong><?= htmlspecialchars($searchName) ?></strong>"
                  <?php endif; ?>
                  <?php if ($filterRate !== 'all'): ?>
                    with <strong><?= ucfirst($filterRate) ?></strong> daily rate
                  <?php endif; ?>
                </span>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Employees Table -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th>LU Ambata Employees</th>
                  <th>Department</th>
                  <th>Daily Rate</th>
                  <th>SSS</th>
                  <th>PHIC</th>
                  <th>HDMF</th>
                  <th>GOVT</th>
                  <th>Email</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($employees) > 0): ?>
                  <?php foreach ($employees as $emp): ?>
                    <tr>
                      <td class="fw-semibold"><?= htmlspecialchars($emp['Name'] ?? '') ?></td>
                      <td><?= htmlspecialchars($emp['Department'] ?? '') ?></td>
                      <td><?= htmlspecialchars($emp['DailyRate'] ?? '') ?></td>
                      <td><?= htmlspecialchars($emp['SSS'] ?? '') ?></td>
                      <td><?= htmlspecialchars($emp['PHIC'] ?? '') ?></td>
                      <td><?= htmlspecialchars($emp['HDMF'] ?? '') ?></td>
                      <td><?= htmlspecialchars($emp['GOVT'] ?? '') ?></td>
                      <td><?= htmlspecialchars($emp['Email'] ?? '') ?></td>
                      <td>
                        <div class="btn-group btn-group-sm">
                          <a href="edit.php?id=<?= $emp['EmployeeID'] ?>" 
                             class="btn btn-outline-primary" 
                             title="Edit">
                            <i class='bx bx-edit-alt'></i>
                          </a>
                          <a href="delete.php?id=<?= $emp['EmployeeID'] ?>" 
                             class="btn btn-outline-danger" 
                             title="Delete"
                             onclick="return confirm('Are you sure you want to delete this employee?')">
                            <i class='bx bx-trash'></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" class="text-center py-4">
                      <i class='bx bx-folder-open' style="font-size: 3rem; opacity: 0.3;"></i>
                      <p class="text-muted-2 mt-2 mb-0">No employees found</p>
                      <?php if ($selectedDept !== 'all'): ?>
                        <p class="text-muted-2 small">Try selecting a different department</p>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// live search
let searchTimeout;
function submitFormDelayed() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(function() {
    document.getElementById('filterForm').submit();
  }, 500); 
}
</script>

<?php include __DIR__.'/partials/footer.php'; ?>