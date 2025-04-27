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

  
//   // Обработчик для кнопки-лупы
// document.querySelector('.mobile-search-toggle').addEventListener('click', function() {
//   const searchInput = document.querySelector('.search_inp');
//   const searchBtn = document.querySelector('.search_btn');
  
//   searchInput.classList.toggle('active');
//   searchBtn.classList.toggle('active');
  
//   if (searchInput.classList.contains('active')) {
//     searchInput.focus();
//   }
// });

// // Закрытие поиска при клике вне области
// document.addEventListener('click', function(e) {
//   if (!e.target.closest('.search') && window.innerWidth <= 800) {
//     document.querySelector('.search_inp').classList.remove('active');
//     document.querySelector('.search_btn').classList.remove('active');
//   }
// });