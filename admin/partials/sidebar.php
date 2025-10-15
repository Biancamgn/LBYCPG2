<?php
$current = basename($_SERVER['PHP_SELF']);
function active($page){ return basename($_SERVER['PHP_SELF']) === $page ? 'active' : ''; }
?>
<aside class="app-sidebar">
  <a href="menu.php" class="brand">
    <i class='bx bxs-briefcase' style='font-size:1.4rem'></i>
    <div>
      <div style="color:#ff9800; font-size:1.5rem; line-height:1.2">LU Ambata</div>
      <div style="color:#ff9800; font-size:0.95rem; line-height:1.2">Payroll System</div>
    </div>
  </a>
  
  <div class="text-muted-2 small mt-4 mb-2" style="font-weight:600; font-size:0.7rem">Main</div>
  
  <nav class="nav flex-column nav-aside">
    <a class="nav-link <?php echo active('menu.php'); ?>" href="menu.php">
      <i class='bx bx-grid-alt me-2'></i>Dashboard
    </a>
    
    <!-- Employees Dropdown -->
    <details class="nav-group">
      <summary>
        <i class='bx bx-group'></i>
        <span>Employees</span>
        <div class="caret"></div>
      </summary>
      <nav class="nav-sub">
        <a href="employees.php" class="nav-sublink <?php echo active('employees.php'); ?>">View Employees</a>
        <a href="edit.php" class="nav-sublink <?php echo active('edit.php'); ?>">Add Employee</a>
      </nav>
    </details>
    
    <!-- Departments -->
    <details class="nav-group">
      <summary>
        <i class='bx bx-briefcase me-2'></i>
        <span>Departments</span>
        <div class="caret"></div>
      </summary>
      <nav class="nav-sub">
        <a href=".php" class="nav-sublink <?php echo active('employees.php'); ?>">Canteen</a>
        <a href="edit.php" class="nav-sublink <?php echo active('edit.php'); ?>">Main Office</a>
        <a href="edit.php" class="nav-sublink <?php echo active('edit.php'); ?>">Sattelite</a>
        <a href="edit.php" class="nav-sublink <?php echo active('edit.php'); ?>">Service Crew</a>
      </nav>
    </details>
    
    <!-- Deductions -->
    <a class="nav-link" href="leave.php">
      <i class='bx bx-calendar-x me-2'></i>Deductions
      <i class='bx bx-chevron-right ms-auto'></i>
    </a>
    
    <!-- Payroll -->
    <a class="nav-link <?php echo active('payroll.php'); ?>" href="payroll.php">
      <i class='bx bx-dollar me-2'></i>Payroll
      <i class='bx bx-chevron-right ms-auto'></i>
    </a>
    
    <!-- Attendance -->
    <a class="nav-link" href="attendance.php">
      <i class='bx bx-time me-2'></i>Attendance
      <i class='bx bx-chevron-right ms-auto'></i>
    </a>
    
    <!-- Announcements -->
    <a class="nav-link" href="announcements.php">
      <i class='bx bx-bullhorn me-2'></i>Announcements
      <i class='bx bx-chevron-right ms-auto'></i>
    </a>
  </nav>
  
  <div class="mt-auto small text-muted-2 pt-3"></div>
</aside>
<?php
?>