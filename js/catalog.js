// открытие и закрытие фильтра
document.querySelector('.filter-mobile-toggle').addEventListener('click', function () {
  document.querySelector('.catalog_filter_content').classList.toggle('active');
});

// Закрытие при клике вне фильтра (опционально)
document.addEventListener('click', function (e) {
  const filter = document.querySelector('.catalog_filter_content');
  const toggleBtn = document.querySelector('.filter-mobile-toggle');

  if (!filter.contains(e.target) && e.target !== toggleBtn) {
    filter.classList.remove('active');
  }
});

// Открытие фильтров
document.querySelector('.filter-mobile-toggle').addEventListener('click', function () {
  document.querySelector('.catalog_filter_content').classList.add('active');
  // document.querySelector('.filters-overlay').classList.add('active');
  document.body.classList.add('menu-open');
});

// Закрытие фильтров
function closeFilters() {
  document.querySelector('.catalog_filter_content').classList.remove('active');
  // document.querySelector('.filters-overlay').classList.remove('active');
  document.body.classList.remove('menu-open');
}

document.querySelector('.close-filters').addEventListener('click', closeFilters);
// document.querySelector('.filters-overlay').addEventListener('click', closeFilters);






// переход на стр каталог через меню из главной
document.addEventListener('DOMContentLoaded', function () {
  // Получаем параметр ?category= из URL
  const urlParams = new URLSearchParams(window.location.search);
  const categoryId = urlParams.get('category');

  // Если параметр есть, находим чекбокс и отмечаем его
  if (categoryId) {
    const checkbox = document.getElementById(categoryId);
    if (checkbox) {
      checkbox.checked = true; // Отмечаем галочку

      // Прокручиваем к этому чекбоксу (опционально)
      setTimeout(() => {
        checkbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 100);
    }
  }

  // Убираем параметр ?category=... из URL после загрузки
  if (window.location.search.includes('category=')) {
    const newUrl = window.location.pathname; // Получаем URL без параметров
    window.history.replaceState(null, null, newUrl); // Заменяем URL без перезагрузки
  }
  // Сброс всех чекбоксов категорий
  if (categoryParam) {
    document.querySelectorAll('.catalog_filter_column input[type="checkbox"]').forEach(checkbox => {
      checkbox.checked = false;
    });
  }



  // Обработка ссылок категорий в хлебных крошках
  document.querySelectorAll('.category-link').forEach(link => {
    link.addEventListener('click', function (e) {
      if (this.getAttribute('href') !== '#') {
        // Сброс всех чекбоксов перед переходом (если нужно)
        document.querySelectorAll('.catalog_filter_column input[type="checkbox"]').forEach(checkbox => {
          checkbox.checked = false;
        });
        // Переход произойдет автоматически по ссылке
      } else {
        e.preventDefault(); // Отменяем переход для неопределенных категорий
      }
    });
  });
});








// работа фильтра
document.addEventListener('DOMContentLoaded', function () {
  // Элементы DOM
  const recipesCards = document.querySelectorAll('.recipes_card');
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  const calorieMinInput = document.querySelector('.paragraph_number:nth-of-type(1) .paragraph_inp_num');
  const calorieMaxInput = document.querySelector('.paragraph_number:nth-of-type(2) .paragraph_inp_num');
  const showMoreBtn = document.querySelector('.recipes_more_btn');

  // Настройки пагинации
  const cardsPerRow = 2;
  const rowsPerLoad = 4;
  const cardsPerLoad = cardsPerRow * rowsPerLoad;
  let visibleCardsCount = cardsPerLoad;

  // Функция проверки активных фильтров
  function hasActiveFilters() {
    const checkedCategoryBoxes = document.querySelectorAll('.catalog_filter_column:nth-of-type(1) input[type="checkbox"]:checked');
    const checkedTimeBoxes = document.querySelectorAll('.catalog_filter_column:nth-of-type(2) input[type="checkbox"]:checked');
    const minCalories = parseInt(calorieMinInput.value) || 0;
    const maxCalories = parseInt(calorieMaxInput.value) || 0;
    const caloriesFilterActive = maxCalories > 0; // Фильтр активен только если указано "до"

    return checkedCategoryBoxes.length > 0 || checkedTimeBoxes.length > 0 || caloriesFilterActive;
  }

  // Функция для получения числового значения калорий из текста
  function getCaloriesValue(caloriesText) {
    const match = caloriesText.match(/\d+(\.\d+)?/);
    return match ? parseFloat(match[0]) : 0;
  }

  // Функция для проверки соответствия времени приготовления
  function matchesTimeFilter(cookingTime, selectedTimes) {
    if (selectedTimes.length === 0) return true;

    const time = parseInt(cookingTime);
    if (isNaN(time)) return false;

    return selectedTimes.some(timeRange => {
      if (timeRange === '< 30 мин') return time < 30;
      if (timeRange === '30-60 мин') return time >= 30 && time <= 60;
      if (timeRange === '> 60 мин') return time > 60;
      return false;
    });
  }

  // Основная функция фильтрации
  function applyFilters() {
    const isFilterActive = hasActiveFilters();

    // 1. Получаем выбранные категории
    const selectedCategories = Array.from(
      document.querySelectorAll('.catalog_filter_column:nth-of-type(1) input[type="checkbox"]:checked')
    ).map(checkbox => {
      return checkbox.nextElementSibling.nextElementSibling.textContent.trim().toLowerCase();
    });

    // 2. Получаем выбранное время
    const selectedTimes = Array.from(
      document.querySelectorAll('.catalog_filter_column:nth-of-type(2) input[type="checkbox"]:checked')
    ).map(checkbox => {
      return checkbox.nextElementSibling.nextElementSibling.textContent.trim();
    });

    // 3. Получаем диапазон калорий (от всегда 0, если не указано иное)
    const minCalories = parseInt(calorieMinInput.value) || 0;
    const maxCalories = parseInt(calorieMaxInput.value) || Infinity;
    let visibleCards = 0;

    // Фильтруем карточки
    recipesCards.forEach((card, index) => {
      // Получаем данные из карточки
      const categoryFromCard = card.querySelector('.recipes_category').textContent
        .replace('Категория:', '')
        .trim()
        .toLowerCase();

      const caloriesText = card.querySelector('.recipes_calory').textContent;
      const calories = getCaloriesValue(caloriesText);
      const cookingTime = card.dataset.cookingTime;

      // Проверяем соответствие всем фильтрам
      const matchesCategory = selectedCategories.length === 0 ||
        selectedCategories.includes(categoryFromCard);
      const matchesCalories = calories >= minCalories && calories <= maxCalories;
      const matchesTime = matchesTimeFilter(cookingTime, selectedTimes);

      // Показываем/скрываем карточку
      if (matchesCategory && matchesCalories && matchesTime) {
        if (isFilterActive) {
          // При активном фильтре показываем все подходящие карточки
          card.style.display = 'flex';
          setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
          }, 10);
        } else {
          // Без фильтров - пагинация
          if (index < visibleCardsCount) {
            card.style.display = 'flex';
            setTimeout(() => {
              card.style.opacity = '1';
              card.style.transform = 'scale(1)';
            }, 10);
          } else {
            card.style.display = 'none';
          }
        }
        visibleCards++;
      } else {
        card.style.opacity = '0';
        card.style.transform = 'scale(0.98)';
        setTimeout(() => {
          card.style.display = 'none';
        }, 300);
      }
    });

    // Управление кнопкой "Показать ещё"
    if (showMoreBtn) {
      if (isFilterActive) {
        showMoreBtn.style.display = 'none';
      } else {
        showMoreBtn.style.display = visibleCardsCount >= visibleCards ? 'none' : 'block';
      }
    }
  }

  // Функция для показа дополнительных карточек
  function showMoreCards() {
    visibleCardsCount += cardsPerLoad;
    applyFilters();
  }

  // Обработчики событий
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', applyFilters);
  });

  // Обработчики для полей калорий
  calorieMinInput.addEventListener('input', function () {
    // Если ввели значение "от", но не ввели "до" - не фильтруем
    if (!calorieMaxInput.value) return;
    applyFilters();
  });

  calorieMaxInput.addEventListener('input', function () {
    // При изменении "до" всегда фильтруем
    applyFilters();
  });

  if (showMoreBtn) {
    showMoreBtn.addEventListener('click', showMoreCards);
  }

  // Инициализация
  function init() {
    recipesCards.forEach(card => {
      card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
      void card.offsetWidth; // Перезапуск анимации
    });

    // Устанавливаем значение по умолчанию для "от"
    calorieMinInput.value = '0';

    applyFilters();
  }

  init();
});







//  ПОИСК и показать еще
document.addEventListener('DOMContentLoaded', function () {
  // Общие элементы
  const searchInput = document.querySelector('.search_inp');
  const searchBtn = document.querySelector('.search_btn');
  const cards = document.querySelectorAll('.recipes_card');
  const showMoreBtn = document.querySelector('.recipes_more_btn');

  // Настройки пагинации
  const cardsPerRow = 4;
  const rowsPerLoad = 2;
  const cardsPerLoad = cardsPerRow * rowsPerLoad;
  let visibleCardsCount = cardsPerRow * 2; // Начальное количество (2 ряда)

  // Инициализация карточек
  function initCards() {
    cards.forEach((card, index) => {
      if (index < visibleCardsCount) {
        card.style.display = 'flex';
        card.classList.remove('hidden');
      } else {
        card.style.display = 'none';
      }
    });
    toggleShowMoreBtn();
  }

  // Поиск карточек
  function performSearch() {
    const query = searchInput.value.trim().toLowerCase();
    let hasMatches = false;

    // Скрываем кнопку "Показать еще" при поиске
    showMoreBtn.style.display = 'none';

    cards.forEach(card => {
      const title = card.querySelector('.recipes_title').textContent.toLowerCase();
      // const category = card.querySelector('.recipes_category').textContent
      //   .replace('Категория:', '').trim().toLowerCase();

      const matches = title.includes(query);

      if (matches) {
        card.style.display = 'flex';
        setTimeout(() => {
          card.classList.remove('hidden');
        }, 10);
        hasMatches = true;
      } else {
        card.classList.add('hidden');
        setTimeout(() => {
          if (card.classList.contains('hidden')) {
            card.style.display = 'none';
          }
        }, 300);
      }
    });

    if (!hasMatches) {
      console.log('Ничего не найдено');
    }
  }

  // Показать еще карточек
  function showMoreCards() {
    visibleCardsCount += cardsPerLoad;
    cards.forEach((card, index) => {
      if (index < visibleCardsCount) {
        card.style.display = 'flex';
      }
    });
    toggleShowMoreBtn();
  }

  // Управление кнопкой "Показать еще"
  function toggleShowMoreBtn() {
    showMoreBtn.style.display = visibleCardsCount >= cards.length ? 'none' : 'block';
  }

  // Сброс к исходному состоянию
  function resetToInitialState() {
    visibleCardsCount = cardsPerRow * 2;
    initCards();
  }

  // Обработчики событий
  searchBtn.addEventListener('click', performSearch);
  showMoreBtn.addEventListener('click', showMoreCards);

  // Поиск при нажатии Enter
  searchInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      performSearch();
    }
  });

  // Автоматический сброс при очистке поля
  searchInput.addEventListener('input', function () {
    if (searchInput.value.trim() === '') {
      resetToInitialState();
    }
  });

  // Инициализация при загрузке
  initCards();
});








// // сохранить/отмена краточки
// document.addEventListener('DOMContentLoaded', function () {
//   // Ключ для localStorage
//   const STORAGE_KEY = 'saved_recipes';

//   // Функция для сохранения состояния в localStorage
//   function updateSavedRecipes(recipeId, isSaved) {
//     let savedRecipes = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

//     if (isSaved) {
//       // Добавляем рецепт, если его нет в списке
//       if (!savedRecipes.includes(recipeId)) {
//         savedRecipes.push(recipeId);
//       }
//     } else {
//       // Удаляем рецепт из списка
//       savedRecipes = savedRecipes.filter(id => id !== recipeId);
//     }

//     localStorage.setItem(STORAGE_KEY, JSON.stringify(savedRecipes));
//   }

//   // Функция для проверки сохраненных рецептов
//   function checkSavedRecipes() {
//     const savedRecipes = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

//     document.querySelectorAll('.recipes_card').forEach(card => {
//       const recipeId = card.dataset.id;
//       const button = card.querySelector('.recipes_btn');

//       if (savedRecipes.includes(recipeId)) {
//         button.textContent = 'Отменить';
//         button.style.backgroundColor = '#cccccc';
//         button.classList.add('saved');
//       }
//     });
//   }

//   // Обработчик кликов для кнопок сохранения
//   document.addEventListener('click', function (e) {
//     if (e.target.classList.contains('recipes_btn')) {
//       const button = e.target;
//       const card = button.closest('.recipes_card');
//       const recipeId = card.dataset.id;
//       const isSaved = button.textContent.trim() === 'Отменить';

//       // Визуальные изменения
//       if (isSaved) {
//         button.textContent = 'Сохранить';
//         button.style.backgroundColor = '';
//         button.classList.remove('saved');
//       } else {
//         button.textContent = 'Отменить';
//         button.style.backgroundColor = '#cccccc';
//         button.classList.add('saved');
//       }

//       // Обновляем localStorage
//       updateSavedRecipes(recipeId, !isSaved);

//       // Здесь будет ваш код для отправки на сервер
//       /*
//       fetch('/save-recipe', {
//         method: 'POST',
//         headers: {
//           'Content-Type': 'application/json',
//         },
//         body: JSON.stringify({
//           recipe_id: recipeId,
//           action: isSaved ? 'unsave' : 'save'
//         })
//       })
//       .then(response => response.json())
//       .then(data => {
//         if (!data.success) {
//           // Откатываем изменения при ошибке
//           button.textContent = isSaved ? 'Отменить' : 'Сохранить';
//           button.style.backgroundColor = isSaved ? '#cccccc' : '';
//           updateSavedRecipes(recipeId, isSaved);
//           alert('Ошибка: ' + data.message);
//         }
//       })
//       .catch(error => {
//         console.error('Error:', error);
//         button.textContent = isSaved ? 'Отменить' : 'Сохранить';
//         button.style.backgroundColor = isSaved ? '#cccccc' : '';
//         updateSavedRecipes(recipeId, isSaved);
//       });
//       */
//     }
//   });

//   // Проверяем сохраненные рецепты при загрузке страницы
//   checkSavedRecipes();
// });





