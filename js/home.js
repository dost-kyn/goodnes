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





// Галочка. При загрузке страницы проверяем сохраненное состояние
// document.addEventListener('DOMContentLoaded', function() {
//   const checkboxes = document.querySelectorAll('input[type="checkbox"][class^="wr-checkbox"]');
//   const savedStates = JSON.parse(localStorage.getItem('checkboxStates') || '{}');

//   checkboxes.forEach(checkbox => {
//     if (savedStates[checkbox.id]) {
//       checkbox.checked = true;
//     }

//     checkbox.addEventListener('change', function() {
//       savedStates[checkbox.id] = this.checked;
//       localStorage.setItem('checkboxStates', JSON.stringify(savedStates));
//     });
//   });
// });


document.addEventListener('DOMContentLoaded', function() {
  // Получаем все чекбоксы с классами, начинающимися на "wr-checkbox"
  const checkboxes = document.querySelectorAll('input[type="checkbox"][class^="wr-checkbox"]');
  
  // Загружаем сохраненные состояния или создаем новый объект
  const savedStates = JSON.parse(localStorage.getItem('checkboxStates')) || {};

  // Применяем сохраненные состояния
  checkboxes.forEach(checkbox => {
    // Используем комбинацию class + id как уникальный ключ
    const storageKey = `${checkbox.className}_${checkbox.id}`;
    
    if (savedStates[storageKey] !== undefined) {
      checkbox.checked = savedStates[storageKey];
    }

    // Добавляем обработчик изменений
    checkbox.addEventListener('change', function() {
      // Обновляем состояние для этого конкретного чекбокса
      savedStates[storageKey] = this.checked;
      
      // Сохраняем обновленные состояния
      localStorage.setItem('checkboxStates', JSON.stringify(savedStates));
      
      console.log(`Сохранено состояние для ${storageKey}:`, this.checked);
    });
  });
});