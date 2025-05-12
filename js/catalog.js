// // Добавьте этот скрипт
// document.querySelector('.filter-mobile-toggle').addEventListener('click', function() {
//     document.querySelector('.catalog_filter_content').classList.toggle('active');
//   });
  
  // Закрытие при клике вне фильтра (опционально)
  document.addEventListener('click', function(e) {
    const filter = document.querySelector('.catalog_filter_content');
    const toggleBtn = document.querySelector('.filter-mobile-toggle');
    
    if (!filter.contains(e.target) && e.target !== toggleBtn) {
      filter.classList.remove('active');
    }
  });

  // Открытие фильтров
document.querySelector('.filter-mobile-toggle').addEventListener('click', function() {
    document.querySelector('.catalog_filter_content').classList.add('active');
    document.querySelector('.filters-overlay').classList.add('active');
    document.body.classList.add('menu-open');
  });
  
  // Закрытие фильтров
  function closeFilters() {
    document.querySelector('.catalog_filter_content').classList.remove('active');
    document.querySelector('.filters-overlay').classList.remove('active');
    document.body.classList.remove('menu-open');
  }
  
  document.querySelector('.close-filters').addEventListener('click', closeFilters);
  document.querySelector('.filters-overlay').addEventListener('click', closeFilters);




