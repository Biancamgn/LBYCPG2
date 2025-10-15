<?php
// partials/topbar.php
?>
<header class="app-topbar">
  <div class="d-flex align-items-center gap-2">
    <button class="btn btn-sm btn-outline-secondary d-lg-none" data-toggle="aside">
      <i class='bx bx-menu'></i>
    </button>
  </div>
  
  <div class="d-flex align-items-center gap-2">
    <a href="edit.php" class="btn btn-warning text-white fw-semibold px-3" style="background:#ff9800; border:none;">
      <i class='bx bx-plus-circle me-1'></i> Add New Employee
    </a>
    <a href="starting.php" class="btn btn-danger fw-semibold px-3">
      Logout
    </a>
    <button class="btn btn-light position-relative" style="width:40px; height:40px; border-radius:8px; display:flex; align-items:center; justify-content:center; padding:0;">
      <i class='bx bx-bell' style="font-size:1.3rem"></i>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem; padding:0.2rem 0.4rem;">
      </span>
    </button>
    <button class="btn btn-light" id="darkModeToggle" style="width:40px; height:40px; border-radius:8px; display:flex; align-items:center; justify-content:center; padding:0;">
      <i class='bx bx-moon' style="font-size:1.3rem"></i>
    </button>
    <button class="btn btn-light" style="width:40px; height:40px; border-radius:8px; display:flex; align-items:center; justify-content:center; padding:0;">
      <i class='bx bx-user' style="font-size:1.3rem"></i>
    </button>
  </div>
</header>

<script>
// Dark mode toggle functionality
const darkModeToggle = document.getElementById('darkModeToggle');
const htmlElement = document.documentElement;

// Check for saved dark mode preference
const currentTheme = localStorage.getItem('theme') || 'light';
if (currentTheme === 'dark') {
  htmlElement.setAttribute('data-theme', 'dark');
  darkModeToggle.innerHTML = '<i class="bx bx-sun" style="font-size:1.3rem"></i>';
}

darkModeToggle.addEventListener('click', function() {
  const currentTheme = htmlElement.getAttribute('data-theme');
  
  if (currentTheme === 'dark') {
    htmlElement.setAttribute('data-theme', 'light');
    localStorage.setItem('theme', 'light');
    this.innerHTML = '<i class="bx bx-moon" style="font-size:1.3rem"></i>';
  } else {
    htmlElement.setAttribute('data-theme', 'dark');
    localStorage.setItem('theme', 'dark');
    this.innerHTML = '<i class="bx bx-sun" style="font-size:1.3rem"></i>';
  }
});
</script>

<main class="app-content">