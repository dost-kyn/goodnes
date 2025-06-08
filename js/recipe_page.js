// кнопка сохранить
// document.addEventListener('DOMContentLoaded', function() {
//   // Ключ для localStorage
//   const STORAGE_KEY = 'saved_recipes';
//   const saveButton = document.querySelector('.main_info_btn');

//   // Проверяем, есть ли кнопка на странице
//   if (!saveButton) return;

//   // Функция для получения ID рецепта (нужно добавить data-id в HTML)
//   function getRecipeId() {
//     // Ищем ближайший элемент с ID рецепта
//     const recipeCard = saveButton.closest('.main_info_content') || 
//                       document.querySelector('.main_info_content');
//     return recipeCard ? recipeCard.dataset.id : null;
//   }

//   // Функция для обновления состояния кнопки
//   function updateSaveButton(isSaved) {
//     saveButton.textContent = isSaved ? 'Отменить' : 'Сохранить';
//     saveButton.style.backgroundColor = isSaved ? '#cccccc' : '';
//     if (isSaved) {
//       saveButton.classList.add('saved');
//     } else {
//       saveButton.classList.remove('saved');
//     }
//   }

//   // Функция для сохранения состояния
//   function updateSavedRecipes(recipeId, isSaved) {
//     let savedRecipes = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

//     if (isSaved) {
//       if (!savedRecipes.includes(recipeId)) {
//         savedRecipes.push(recipeId);
//       }
//     } else {
//       savedRecipes = savedRecipes.filter(id => id !== recipeId);
//     }

//     localStorage.setItem(STORAGE_KEY, JSON.stringify(savedRecipes));
//   }

//   // Проверяем сохраненные рецепты при загрузке
//   function checkSavedRecipe() {
//     const recipeId = getRecipeId();
//     if (!recipeId) return;

//     const savedRecipes = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
//     const isSaved = savedRecipes.includes(recipeId);
//     updateSaveButton(isSaved);
//   }

//   // Обработчик клика
//   saveButton.addEventListener('click', function() {
//     const recipeId = getRecipeId();
//     if (!recipeId) return;

//     const isSaved = saveButton.textContent.trim() === 'Отменить';

//     // Визуальные изменения
//     updateSaveButton(!isSaved);

//     // Обновляем хранилище
//     updateSavedRecipes(recipeId, !isSaved);

//     // Отправка на сервер (раскомментировать при необходимости)
//     /*
//     fetch('/save-recipe', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//       },
//       body: JSON.stringify({
//         recipe_id: recipeId,
//         action: isSaved ? 'unsave' : 'save'
//       })
//     })
//     .then(response => response.json())
//     .then(data => {
//       if (!data.success) {
//         // Откатываем изменения при ошибке
//         updateSaveButton(isSaved);
//         updateSavedRecipes(recipeId, isSaved);
//         alert('Ошибка: ' + data.message);
//       }
//     })
//     .catch(error => {
//       console.error('Error:', error);
//       updateSaveButton(isSaved);
//       updateSavedRecipes(recipeId, isSaved);
//     });
//     */
//   });

//   // Проверяем состояние при загрузке
//   checkSavedRecipe();
// });






// Функции для показа уведомлений
document.addEventListener('DOMContentLoaded', function () {
  // Элементы DOM
  const loginForm = document.querySelector('.save_auto');
  const emailInput = document.querySelector('.save_auto_inp[type="text"]');
  const nameInput = document.querySelector('.save_auto_inp[type="text"]:nth-of-type(2)');
  const loginButton = document.querySelector('.save_auto_btn');

  // Создаем элементы для отображения ошибок
  const emailError = document.createElement('div');
  emailError.className = 'error-message';
  emailInput.parentNode.insertBefore(emailError, emailInput.nextSibling);

  const nameError = document.createElement('div');
  nameError.className = 'error-message';
  nameInput.parentNode.insertBefore(nameError, nameInput.nextSibling);

  // Создаем уведомления
  const emptyFieldsNotification = document.createElement('div');
  emptyFieldsNotification.className = 'notification notification-warning';
  emptyFieldsNotification.id = 'empty-fields';
  emptyFieldsNotification.textContent = 'Пожалуйста, заполните все поля (email и имя)';
  document.querySelector('.notification-container').appendChild(emptyFieldsNotification);

  // Функция валидации email
  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  // Функции для показа/скрытия ошибок
  function showError(input, message) {
    const errorElement = input.nextElementSibling;
    errorElement.textContent = message;
    input.style.borderColor = '#ff4444';
  }

  function hideError(input) {
    const errorElement = input.nextElementSibling;
    errorElement.textContent = '';
    input.style.borderColor = '';
  }

  // Функции для показа уведомлений
  function showNotification(id) {
    const notification = document.getElementById(id);
    notification.style.display = 'block';
    setTimeout(() => notification.style.display = 'none', 5000);
  }

  // Обработчик кнопки входа
  loginButton.addEventListener('click', function (e) {
    e.preventDefault();
    let isValid = true;

    // Сбрасываем предыдущие ошибки
    hideError(emailInput);
    hideError(nameInput);

    // Проверка имени
    if (nameInput.value.trim() === '') {
      showError(nameInput, 'Пожалуйста, введите ваше имя');
      isValid = false;
    }

    // Проверка email
    if (emailInput.value.trim() === '') {
      showError(emailInput, 'Пожалуйста, введите email');
      isValid = false;
    } else if (!validateEmail(emailInput.value.trim())) {
      showError(emailInput, 'Пожалуйста, введите корректный email');
      isValid = false;
    }

    if (!isValid) {
      showNotification('empty-fields');
      return;
    }

    // Здесь будет ваша PHP-логика
    // showNotification('login-success');
  });
  
  // Проверка статуса входа
  function checkLoginStatus() {
    const isLoggedIn = false;
    if (isLoggedIn) {
      showNotification('already-logged-in');
      loginForm.style.display = 'none';
    }
  }
  
  // Проверяем статус при загрузке страницы
  checkLoginStatus();
});





document.addEventListener('DOMContentLoaded', function() {
    // Элементы для отображения отзывов
    const reviewContent = document.querySelector('.review_content');
    const reviewBoxes = document.querySelectorAll('.review_box');
    const reviewMoreBtn = document.querySelector('.review_more_btn');
    const reviewQuantity = document.querySelector('.review_quan_num');
    
    // Элементы для формы отзыва
    const reviewLeaveBtn = document.querySelector('.review_leave_btn');
    const modalReview = document.querySelector('.modal-new_review');
    const cancelBtn = document.querySelector('.back');
    const publishBtn = document.querySelector('.start');
    const reviewTextarea = document.querySelector('.new_review_inp');

    // Настройки отображения отзывов
    const reviewsPerRow = 2;
    const rowsPerLoad = 2;
    const reviewsPerLoad = reviewsPerRow * rowsPerLoad;
    let visibleReviewsCount = reviewsPerRow;

    // ===== Основные функции =====
    
    // Инициализация отзывов
    function initReviews() {
        if (!reviewContent || !reviewBoxes.length) return;
        
        reviewBoxes.forEach((box, index) => {
            box.style.display = index < visibleReviewsCount ? 'block' : 'none';
        });
        
        toggleMoreButton();
        updateReviewCount();
    }

    // Показать еще отзывы
    function showMoreReviews() {
        if (!reviewMoreBtn) return;
        
        visibleReviewsCount += reviewsPerLoad;
        document.querySelectorAll('.review_box').forEach((box, index) => {
            box.style.display = index < visibleReviewsCount ? 'block' : 'none';
        });
        
        toggleMoreButton();
    }

    // Управление кнопкой "Показать еще"
    function toggleMoreButton() {
        if (!reviewMoreBtn) return;
        
        const allReviews = document.querySelectorAll('.review_box');
        reviewMoreBtn.style.display = visibleReviewsCount >= allReviews.length ? 'none' : 'block';
    }

    // Обновление счетчика отзывов
    function updateReviewCount() {
        if (!reviewQuantity) return;
        
        const reviewBoxes = document.querySelectorAll('.review_box');
        reviewQuantity.textContent = reviewBoxes.length;
    }

    // Добавление нового отзыва (обновленная версия)
    function addNewReview(text, userName = 'Вы') {
        if (!reviewContent) return;
        
        const newReview = document.createElement('div');
        newReview.className = 'review_box';
        newReview.style.opacity = '0';
        newReview.style.transform = 'scale(0.9)';
        newReview.style.transition = 'all 0.3s ease';
        
        const date = new Date();
        const formattedDate = `${date.getDate().toString().padStart(2, '0')}.${(date.getMonth()+1).toString().padStart(2, '0')}.${date.getFullYear()}`;
        
        newReview.innerHTML = `
            <div class="review_box_info">
                <span class="review_name">${userName}</span>
                <span class="review_date">${formattedDate}</span>
            </div>
            <p class="review_box_text">${text}</p>
        `;
        
        reviewContent.insertBefore(newReview, reviewContent.firstChild);
        
        setTimeout(() => {
            newReview.style.opacity = '1';
            newReview.style.transform = 'scale(1)';
        }, 10);
        
        updateReviewCount();
        showMoreReviews(); // Автоматически показываем новые отзывы
    }

    // ===== Обработчики событий =====
    
    // Открытие модального окна
    if (reviewLeaveBtn) {
        reviewLeaveBtn.addEventListener('click', function() {
            if (modalReview) modalReview.classList.add('active');
            if (reviewTextarea) reviewTextarea.focus();
        });
    }

    // Закрытие модального окна
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            if (modalReview) modalReview.classList.remove('active');
            if (reviewTextarea) reviewTextarea.value = '';
        });
    }

    // Закрытие по клику вне окна
    if (modalReview) {
        modalReview.addEventListener('click', function(e) {
            if (e.target === modalReview) {
                modalReview.classList.remove('active');
                if (reviewTextarea) reviewTextarea.value = '';
            }
        });
    }


// Публикация отзыва (обновленная версия)
if (publishBtn) {
    publishBtn.addEventListener('click', async function() {
        const reviewText = reviewTextarea ? reviewTextarea.value.trim() : '';
        const recipeId = document.querySelector('.main_info_content')?.dataset.id;
        
        if (!reviewText) {
            alert('Пожалуйста, напишите текст отзыва');
            return;
        }

        if (!recipeId) {
            alert('Не удалось определить рецепт');
            return;
        }

        // Показываем индикатор загрузки
        publishBtn.disabled = true;
        publishBtn.textContent = 'Отправка...';

        try {
            const response = await fetch('/connect/add_review.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    recipe_id: recipeId,
                    text: reviewText
                })
            });

            // Проверяем Content-Type ответа
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Ожидался JSON, но получен: ${contentType}. Ответ: ${text}`);
            }

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Ошибка сервера');
            }

            if (data.success) {
                // Добавляем новый отзыв в список
                addNewReview(reviewText, data.user_name, data.avatar);
                
                // Закрываем модальное окно
                if (modalReview) modalReview.classList.remove('active');
                if (reviewTextarea) reviewTextarea.value = '';
                
                // Обновляем счетчик отзывов
                updateReviewsCount();
            } else {
                throw new Error(data.message || 'Неизвестная ошибка');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ошибка: ' + error.message);
        } finally {
            publishBtn.disabled = false;
            publishBtn.textContent = 'Опубликовать';
        }
    });
}


// Функция для добавления нового отзыва в DOM
function addNewReview(text, userName) {
    const reviewsContainer = document.querySelector('.reviews_list');
    if (!reviewsContainer) return;

    const newReview = document.createElement('div');
    newReview.className = 'review_item';
    newReview.innerHTML = `
        <div class="review_user">
            <span class="review_user_name">${userName}</span>
            <span class="review_date">Только что</span>
        </div>
        <div class="review_text">${text}</div>
    `;
    
    reviewsContainer.prepend(newReview);
}

// Функция для обновления счетчика отзывов
function updateReviewsCount() {
    const counter = document.querySelector('.review_quan_num');
    if (counter) {
        const currentCount = parseInt(counter.textContent) || 0;
        counter.textContent = currentCount + 1;
    }
}
    // Кнопка "Показать еще"
    if (reviewMoreBtn) {
        reviewMoreBtn.addEventListener('click', showMoreReviews);
    }

    // Инициализация при загрузке
    initReviews();
});