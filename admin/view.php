<?php
require __DIR__ . '/db.php';


/**
 * Prefer EmployeeNo if it exists; otherwise use DataEntryID.
 */
$empCol = 'DataEntryID';
if ($res = $sql->query("SELECT COLUMN_NAME
                          FROM INFORMATION_SCHEMA.COLUMNS
                         WHERE TABLE_SCHEMA = DATABASE()
                           AND TABLE_NAME = 'employeedetails'
                           AND COLUMN_NAME = 'EmployeeNo'")) {
  if ($res && $res->num_rows > 0) { $empCol = 'EmployeeNo'; }
}

/**
 * Filters (keep layout): q (name), shift (1/2/3), duty (OnDuty/Late/Overtime), date_from, date_to
 */
$q         = trim($_GET['q'] ?? '');
$shift     = $_GET['shift'] ?? '';
$duty      = $_GET['duty'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to'] ?? '';

/**
 * Build filtered query with prepared params.
 * Always ORDER BY employee number column.
 */
$sqlStr = "SELECT * FROM employeedetails WHERE 1=1";
$params = [];
$types  = "";

if ($q !== '') {
  $sqlStr .= " AND (FirstName LIKE CONCAT('%', ?, '%') OR LastName LIKE CONCAT('%', ?, '%'))";
  $params[] = $q; $params[] = $q; $types .= "ss";
}
if ($shift !== '' && in_array($shift, ['1','2','3'], true)) {
  $sqlStr .= " AND ShiftNo = ?";
  $params[] = (int)$shift; $types .= "i";
}
if ($duty !== '' && in_array($duty, ['OnDuty','Late','Overtime'], true)) {
  $sqlStr .= " AND DutyType = ?";
  $params[] = $duty; $types .= "s";
}
if ($date_from !== '') {
  $sqlStr .= " AND ShiftDate >= ?";
  $params[] = $date_from; $types .= "s";
}
if ($date_to !== '') {
  $sqlStr .= " AND ShiftDate <= ?";
  $params[] = $date_to; $types .= "s";
}

$sqlStr .= " ORDER BY $empCol ASC";

$stmt = $sql->prepare($sqlStr);
if ($types) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$res  = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);

/**
 * CSV export: exports exactly the CURRENT filtered list ($rows).
 * Force Shift Date to text (="YYYY-MM-DD") to prevent Excel "#######".
 */
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="employee_records_' . date('Y-m-d') . '.csv"');
  // UTF-8 BOM for Excel
  echo "\xEF\xBB\xBF";

  $out = fopen('php://output', 'w');
  fputcsv($out, ['Employee Number', 'Last Name', 'First Name', 'Shift Date', 'Shift No', 'Hours', 'Duty Type']);

  foreach ($rows as $r) {
    // Excel-safe date as TEXT: ="YYYY-MM-DD"
    $dateTxt = '';
    if (!empty($r['ShiftDate'])) {
      $dateTxt = '="' . $r['ShiftDate'] . '"';
    }
    fputcsv($out, [
      $r[$empCol] ?? $r['DataEntryID'] ?? '',
      $r['LastName']  ?? '',
      $r['FirstName'] ?? '',
      $dateTxt,
      $r['ShiftNo']   ?? '',
      $r['Hours']     ?? '',
      $r['DutyType']  ?? '',
    ]);
  }
  fclose($out);
  exit();
}

/**
 * AJAX endpoint for live search (used by JS below)
 */
if (isset($_GET['ajax'])) {
  header('Content-Type: application/json; charset=utf-8');
  $data = [];
  foreach ($rows as $r) {
    $data[] = [
      'EmployeeNo' => $r[$empCol] ?? null,
      'DataEntryID'=> $r['DataEntryID'] ?? null,
      'LastName'   => $r['LastName'] ?? '',
      'FirstName'  => $r['FirstName'] ?? '',
      'ShiftDate'  => $r['ShiftDate'] ?? '',
      'ShiftNo'    => isset($r['ShiftNo']) ? (int)$r['ShiftNo'] : null,
      'Hours'      => isset($r['Hours'])   ? (int)$r['Hours']   : null,
      'DutyType'   => $r['DutyType'] ?? ''
    ];
  }
  echo json_encode(['count'=>count($data), 'rows'=>$data, 'empCol'=>$empCol], JSON_UNESCAPED_UNICODE);
  exit();
}

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/sidebar.php';
include __DIR__ . '/partials/topbar.php';
?>

<div class="container-fluid">
  <div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <h5 class="mb-0">Employee Records</h5>
        <span class="badge bg-secondary" id="countBadge"><?php echo count($rows); ?> found</span>
      </div>
      <div class="d-flex gap-2">
        <a href="#" id="exportCsv" class="btn btn-outline-secondary">
          <i class='bx bx-export me-1'></i>Export to Excel
        </a>
      </div>
    </div>

    <div class="card-body">
      <form class="row g-2" id="filtersForm">
        <div class="col-md-4">
          <label class="form-label small text-muted">Search Name</label>
          <input type="text"
                 name="q"
                 id="q"
                 value="<?php echo htmlspecialchars($q); ?>"
                 class="form-control"
                 placeholder="Type to search..."
                 autocomplete="off" />
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted">Shift No</label>
          <select name="shift" id="shift" class="form-select">
            <option value="">All</option>
            <?php foreach(['1','2','3'] as $s): ?>
              <option value="<?php echo $s; ?>" <?php echo $shift===$s?'selected':''; ?>><?php echo $s; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted">Duty Type</label>
          <select name="duty" id="duty" class="form-select">
            <option value="">All</option>
            <?php foreach(['OnDuty','Overtime','Late'] as $d): ?>
              <option value="<?php echo $d; ?>" <?php echo $duty===$d?'selected':''; ?>><?php echo $d; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted">From</label>
          <input type="date" name="date_from" id="date_from" value="<?php echo htmlspecialchars($date_from); ?>" class="form-control"/>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted">To</label>
          <input type="date" name="date_to" id="date_to" value="<?php echo htmlspecialchars($date_to); ?>" class="form-control"/>
        </div>
        <div class="col-12 d-flex gap-2 mt-2">
          <button class="btn btn-primary"><i class='bx bx-search me-1'></i>Filter</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="resultsTable">
        <thead>
          <tr>
            <th>Employee #</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Shift Date</th>
            <th>Shift No</th>
            <th>Hours</th>
            <th>Duty</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody id="resultsBody">
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?php echo htmlspecialchars($r[$empCol] ?? $r['DataEntryID']); ?></td>
              <td><?php echo htmlspecialchars($r['LastName'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($r['FirstName'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($r['ShiftDate'] ?? ''); ?></td>
              <td><span class="badge rounded-pill text-bg-primary"><?php echo (int)($r['ShiftNo'] ?? 0); ?></span></td>
              <td><?php echo (int)($r['Hours'] ?? 0); ?></td>
              <td>
                <?php
                  $d = $r['DutyType'] ?? '';
                  $cls = $d==='OnDuty'?'badge-soft-success':($d==='Late'?'badge-soft-warning':'badge-soft-danger');
                ?>
                <span class="badge badge-soft <?php echo $cls; ?>"><?php echo htmlspecialchars($d); ?></span>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="edit.php?id=<?php echo (int)($r['DataEntryID'] ?? 0); ?>"><i class='bx bx-edit-alt'></i></a>
                <a class="btn btn-sm btn-outline-danger" href="delete.php?id=<?php echo (int)($r['DataEntryID'] ?? 0); ?>"><i class='bx bx-trash'></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!count($rows)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">No records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<style>
  .card { border-radius: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,.05) }
  .table thead th{ font-size:.8rem; text-transform:uppercase; letter-spacing:.06em; color:#6c757d }
  .badge-soft{ border:1px solid currentColor; background:transparent }
  .badge-soft-success{ color:#71dd37 } .badge-soft-warning{ color:#ffab00 } .badge-soft-danger{ color:#ff3e1d }
</style>

<script>
(function(){
  const form  = document.getElementById('filtersForm');
  const q     = document.getElementById('q');
  const shift = document.getElementById('shift');
  const duty  = document.getElementById('duty');
  const df    = document.getElementById('date_from');
  const dt    = document.getElementById('date_to');

  const tbody = document.getElementById('resultsBody');
  const countBadge = document.getElementById('countBadge');
  const exportBtn  = document.getElementById('exportCsv');

  function paramsFromForm(includeAjax = true){
    const fd = new FormData(form);
    const p  = new URLSearchParams(fd);
    if (includeAjax) p.set('ajax','1'); 
    return p.toString();
  }

  function escapeHtml(s){
    return String(s)
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'","&#39;");
  }

  function dutyBadge(d){
    const map = {OnDuty:'badge-soft-success', Late:'badge-soft-warning', Overtime:'badge-soft-danger'};
    return `<span class="badge badge-soft ${map[d]||'badge-soft-primary'}">${d||''}</span>`;
  }

  function render(rows, empCol){
    tbody.innerHTML = rows.map(r => `
      <tr>
        <td>${escapeHtml(r[empCol] ?? r.DataEntryID ?? '')}</td>
        <td>${escapeHtml(r.LastName ?? '')}</td>
        <td>${escapeHtml(r.FirstName ?? '')}</td>
        <td>${escapeHtml(r.ShiftDate ?? '')}</td>
        <td><span class="badge rounded-pill text-bg-primary">${r.ShiftNo ?? ''}</span></td>
        <td>${r.Hours ?? ''}</td>
        <td>${dutyBadge(r.DutyType)}</td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-primary" href="edit.php?id=${r.DataEntryID ?? ''}"><i class='bx bx-edit-alt'></i></a>
          <a class="btn btn-sm btn-outline-danger" href="delete.php?id=${r.DataEntryID ?? ''}"><i class='bx bx-trash'></i></a>
        </td>
      </tr>
    `).join('');
  }

  let timer;
  async function fetchLive(){
    const url = 'view.php?' + paramsFromForm(true);
    const res = await fetch(url, { headers: { 'Accept':'application/json' } });
    if (!res.ok) return;
    const data = await res.json();
    render(data.rows, data.empCol);
    if (countBadge) countBadge.textContent = data.count + ' found';
  }
  function debounce(){ clearTimeout(timer); timer = setTimeout(fetchLive, 250); }

  // Live type
  if (q) q.addEventListener('input', debounce);
  [shift, duty, df, dt].forEach(el => el && el.addEventListener('change', fetchLive));

  // Export filtered/typed
  if (exportBtn) {
    exportBtn.addEventListener('click', function (e) {
      e.preventDefault();
      const query = paramsFromForm(false); 
      const url = 'view.php?' + (query ? (query + '&') : '') + 'export=csv';
      window.location.href = url;
    });
  }
})();
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>


