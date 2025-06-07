const toggleTheme = document.querySelector(".header_nav_tema"); //берем кнопку по смене темы 
const toggleTheme1 = document.querySelector(".mobile-menu-theme");
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

// Клик по переключателю2
toggleTheme1.addEventListener('click', () => {
  currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
  localStorage.setItem('theme', currentTheme);
  document.documentElement.setAttribute('data-theme', currentTheme);
  updateThemeImages(currentTheme);
});

// Запуск при загрузке
document.addEventListener('DOMContentLoaded', initTheme);




// меня в хедере при маленьком размере экрана
    document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.querySelector('.header_nav_btn');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
    const closeBtn = document.querySelector('.mobile-menu-close');
    
    // Открытие меню
    menuBtn.addEventListener('click', function() {
        mobileMenu.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
    
    // Закрытие меню
    function closeMenu() {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    closeBtn.addEventListener('click', closeMenu);
    mobileMenuOverlay.addEventListener('click', closeMenu);
    
    // Закрытие при нажатии на Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMenu();
        }
    });
    
    // Переключение темы в мобильном меню
    const themeBtn = document.querySelector('.mobile-menu-theme');
    if (themeBtn) {
        themeBtn.addEventListener('click', function() {
            // Ваш код для переключения темы
            console.log('Смена темы из мобильного меню');
        });
    }
});





// В ФУТОРЕ перенаправление в каталог с категорией
document.addEventListener('DOMContentLoaded', function() {
  // Соответствие категорий (учитываем возможные варианты написания)
  const categoryMapping = {
    'кексы': 'wr4',
    'пироги': 'wr3',
    'хлеб': 'wr6',
    'торты': 'wr1',
    'конфеты': 'wr5',
    'печенье': 'wr2'
  };

  // Находим все ссылки категорий в футере
  const categoryLinks = document.querySelectorAll('.footer_cataloge_link');

  // Добавляем обработчики
  categoryLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault(); // Отменяем стандартное поведение ссылки
      
      // Получаем текст ссылки и приводим к нижнему регистру
      const categoryName = this.textContent.trim().toLowerCase();
      
      // Находим соответствующий ID категории
      const categoryId = categoryMapping[categoryName];
      
      if (categoryId) {
        // Перенаправляем на страницу каталога
        window.location.href = `catalog.php?category=${categoryId}`;
      } else {
        console.warn('Категория не найдена:', categoryName);
        // Можно добавить fallback-редирект
        window.location.href = 'catalog.php';
      }
    });
  });
});









//     галочки
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