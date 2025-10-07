<?php

$current = basename($_SERVER['PHP_SELF']);
function active($page){ return basename($_SERVER['PHP_SELF']) === $page ? 'active' : ''; }
?>
<aside class="app-sidebar">
  <div class="text-muted-2 small">LBYCPG2 • EQ3</div>
  <hr class="opacity-10" />
  <nav class="nav flex-column nav-aside">
    <a class="nav-link <?php echo active('menu.php'); ?>" href="menu.php"><i class='bx bx-home-alt-2 me-2'></i>Dashboard</a>
    <a class="nav-link <?php echo active('view.php'); ?>" href="view.php"><i class='bx bx-table me-2'></i>Employee Records</a>
    <a class="nav-link <?php echo active('edit.php'); ?>" href="edit.php"><i class='bx bx-edit-alt me-2'></i>Add Record</a>
  </nav>
  <div class="mt-auto small text-muted-2 pt-3">
  </div>
</aside>

0


