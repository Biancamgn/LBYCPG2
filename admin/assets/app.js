
// Sidebar toggler for mobile
document.addEventListener('DOMContentLoaded', () => {
  const toggler = document.querySelector('[data-toggle="aside"]');
  const aside = document.querySelector('.app-sidebar');
  if (toggler && aside){
    toggler.addEventListener('click', () => aside.classList.toggle('open'));
  }
});
