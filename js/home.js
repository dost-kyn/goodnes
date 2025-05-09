const toggleTheme = document.querySelector(".header_nav_tema"); //берем кнопку по смене темы
let currentTheme = localStorage.getItem('theme') || 'light'; //Если значение 'theme' - ложно, вернет 'light'

function updateThemeImages(theme) {
  const themeImages = document.querySelectorAll('[data-theme-image]'); // все объекты с параметром data-theme-image
  
  themeImages.forEach(img => {
    if (!img.dataset.light || !img.dataset.dark) {  //Обращение к data-атрибутам элемента <img>: data-light="/image/cake_icon.svg"  data-dark="/image/cake_icon-dark.svg"
      console.warn('Отсутствуют data-атрибуты у:', img);
      return;
    }

    
    let newSrc;
    if (theme === 'dark') {
      newSrc = img.dataset.dark;
    } else {
      newSrc = img.dataset.light;
    }
    
    // Меняем src если нужно
    if (img.src !== newSrc) {
        img.src = newSrc;
    }
  });
}

// Инициализация темы
function initTheme() {
  if (currentTheme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
  }
  updateThemeImages(currentTheme);
}

// Клик по переключателю
toggleTheme.addEventListener('click', () => {
  currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
  localStorage.setItem('theme', currentTheme);
  document.documentElement.setAttribute('data-theme', currentTheme);
  updateThemeImages(currentTheme);
});

// Запуск при загрузке
document.addEventListener('DOMContentLoaded', initTheme);





// // Галочка. При загрузке страницы проверяем сохраненное состояние
// document.addEventListener('DOMContentLoaded', function() {
//   // Находим чекбокс
//   const checkbox = document.querySelector('.wr-checkbox');
//   const uniqueKey = `checkboxState_${checkbox.id}`; // Используем id чекбокса
// localStorage.setItem(uniqueKey, this.checked ? 'checked' : 'unchecked');
  
//   // Проверяем сохранённое состояние в localStorage
//   const savedState = localStorage.getItem('checkboxState');
//   if (savedState === 'checked') {
//     checkbox.checked = true; // Восстанавливаем галочку
//   }
  
//   // Обработчик клика
//   checkbox.addEventListener('change', function() {
//     // Сохраняем состояние в localStorage
//     localStorage.setItem('checkboxState', this.checked ? 'checked' : 'unchecked');
//   });
// });

// Пример хранения всех состояний в одном объекте
document.addEventListener('DOMContentLoaded', function() {
  const checkboxes = document.querySelectorAll('input[type="checkbox"][class^="wr-checkbox"]');
  const savedStates = JSON.parse(localStorage.getItem('checkboxStates') || '{}');

  checkboxes.forEach(checkbox => {
    if (savedStates[checkbox.id]) {
      checkbox.checked = true;
    }

    checkbox.addEventListener('change', function() {
      savedStates[checkbox.id] = this.checked;
      localStorage.setItem('checkboxStates', JSON.stringify(savedStates));
    });
  });
});