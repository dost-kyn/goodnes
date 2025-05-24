document.addEventListener('DOMContentLoaded', function() {
  // Общие элементы DOM
  const searchBtn = document.querySelector('.search_btn');
  const searchInput = document.querySelector('.search_inp');
  const showMoreBtn = document.querySelector('.recipes_more_btn');
  let recipesCards = document.querySelectorAll('.recipes_card');
  
  // Настройки пагинации
  const cardsPerRow = 5;
  const rowsPerLoad = 2;
  const cardsPerLoad = cardsPerRow * rowsPerLoad;
  let visibleCardsCount = cardsPerRow * 2; // Начальное количество (2 ряда)

  // Основные функции
  function initCards() {
    recipesCards = document.querySelectorAll('.recipes_card'); // Обновляем список
    const totalCards = recipesCards.length;
    
    // Если карточек меньше или равно 10, показываем все
    if (totalCards <= cardsPerLoad) {
      visibleCardsCount = totalCards;
      showAllCards();
      showMoreBtn.style.display = 'none';
    } 
    // Если больше 10, показываем первые 10
    else {
      visibleCardsCount = cardsPerLoad;
      showInitialCards();
      showMoreBtn.style.display = 'block';
    }
  }

  function showAllCards() {
    recipesCards.forEach(card => {
      showCard(card);
    });
  }

  function showInitialCards() {
    recipesCards.forEach((card, index) => {
      if (index < visibleCardsCount) {
        showCard(card);
      } else {
        hideCard(card, true);
      }
    });
  }

  function performSearch() {
    const query = searchInput.value.trim().toLowerCase();
    let hasMatches = false;

    showMoreBtn.style.display = 'none';

    recipesCards.forEach(card => {
      const title = card.querySelector('.recipes_title').textContent.toLowerCase();
      const category = card.querySelector('.recipes_category').textContent
        .replace('Категория:', '').trim().toLowerCase();
      
      const matches = title.includes(query) || category.includes(query);

      if (matches) {
        showCard(card);
        hasMatches = true;
      } else {
        hideCard(card);
      }
    });

    if (!hasMatches) {
      console.log('Ничего не найдено');
    }
  }

  function showMoreCards() {
    visibleCardsCount += cardsPerLoad;
    updateVisibleCards();
    toggleShowMoreBtn();
  }

  function updateVisibleCards() {
    recipesCards.forEach((card, index) => {
      if (index < visibleCardsCount) {
        showCard(card);
      }
    });
  }

  function toggleShowMoreBtn() {
    if (!showMoreBtn) return;
    
    recipesCards = document.querySelectorAll('.recipes_card');
    showMoreBtn.style.display = visibleCardsCount >= recipesCards.length ? 'none' : 'block';
  }

  function resetToInitialState() {
    initCards(); // Полностью переинициализируем карточки
  }

  // Вспомогательные функции для работы с карточками
  function showCard(card) {
    card.style.display = 'flex';
    setTimeout(() => {
      card.style.opacity = '1';
      card.style.transform = 'scale(1)';
    }, 10);
  }

  function hideCard(card, immediate = false) {
    if (immediate) {
      card.style.display = 'none';
    } else {
      card.style.opacity = '0';
      card.style.transform = 'scale(0.98)';
      setTimeout(() => {
        if (parseFloat(card.style.opacity) === 0) {
          card.style.display = 'none';
        }
      }, 300);
    }
  }

  // Обработчики событий
  searchBtn.addEventListener('click', performSearch);
  
  if (showMoreBtn) {
    showMoreBtn.addEventListener('click', showMoreCards);
  }

  searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') performSearch();
  });

  searchInput.addEventListener('input', function() {
    if (searchInput.value.trim() === '') {
      resetToInitialState();
    }
  });

  // Обработчик удаления карточек
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('recipes_btn')) {
      const card = e.target.closest('.recipes_card');
      
      // Анимация удаления
      card.style.opacity = '0';
      card.style.transform = 'scale(0.9)';
      card.style.transition = 'all 0.3s ease';
      
      setTimeout(() => {
        card.remove();
        // Полная переинициализация после удаления
        resetToInitialState();
      }, 300);
    }
  });

  // Инициализация при загрузке
  initCards();
});







// -------- отзывы
document.addEventListener('DOMContentLoaded', function() {
  // Элементы DOM
  const reviewContent = document.querySelector('.review_content');
  const reviewCount = document.querySelector('.review_quan_num');
  const showMoreBtn = document.querySelector('.review_more_btn');
  
  // Настройки пагинации
  const reviewsPerRow = 2;
  const rowsPerLoad = 2;
  const reviewsPerLoad = reviewsPerRow * rowsPerLoad; // 4 отзыва
  let visibleReviewsCount = reviewsPerLoad; // Начальное количество
  
  // Обновление счетчика отзывов
  function updateReviewCount() {
    const allReviews = document.querySelectorAll('.review_box');
    reviewCount.textContent = allReviews.length;
    return allReviews;
  }
  
  // Полная переинициализация отзывов
  function reinitializeReviews() {
    const allReviews = updateReviewCount();
    const normalReviews = Array.from(allReviews).filter(review => !review.classList.contains('ban'));
    
    // Сначала показываем заблокированные отзывы
    document.querySelectorAll('.review_box.ban').forEach(review => {
      review.style.display = 'block';
      review.style.order = '-1'; // Перемещаем в начало
    });
    
    // Затем обычные отзывы
    if (normalReviews.length <= reviewsPerLoad) {
      // Если отзывов 4 или меньше - показываем все
      visibleReviewsCount = normalReviews.length;
      normalReviews.forEach(review => {
        review.style.display = 'block';
      });
      showMoreBtn.style.display = 'none';
    } else {
      // Если больше 4 - показываем первые 4
      visibleReviewsCount = reviewsPerLoad;
      normalReviews.forEach((review, index) => {
        review.style.display = index < visibleReviewsCount ? 'block' : 'none';
      });
      showMoreBtn.style.display = 'block';
    }
  }
  
  // Показать еще отзывов
  function showMoreReviews() {
    const normalReviews = Array.from(document.querySelectorAll('.review_box:not(.ban)'));
    visibleReviewsCount += reviewsPerLoad;
    
    normalReviews.forEach((review, index) => {
      review.style.display = index < visibleReviewsCount ? 'block' : 'none';
    });
    
    // Скрываем кнопку если показали все
    showMoreBtn.style.display = visibleReviewsCount >= normalReviews.length ? 'none' : 'block';
  }
  
  // Таймер для заблокированных отзывов
  function setupBanTimers() {
    document.querySelectorAll('.review_box.ban').forEach(review => {
      let timeLeft = 3 * 60 * 60; // 3 часа в секундах
      const timerElement = review.querySelector('.review_box_text_ban');
      
      const timer = setInterval(() => {
        timeLeft--;
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;
        
        timerElement.textContent = `Ваш отзыв заблокирован. Удаление через: ${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}\nПричина: Спам`;
        
        if (timeLeft <= 0) {
          clearInterval(timer);
          review.remove();
          reinitializeReviews(); // Полная переинициализация после удаления
        }
      }, 1000);
    });
  }
  
  // Модальное окно подтверждения удаления
  function createConfirmModal(review) {
    const modal = document.createElement('div');
    modal.className = 'confirm-modal';
    modal.innerHTML = `
      <div class="confirm-modal-content">
        <p>Вы точно хотите удалить этот отзыв?</p>
        <div class="confirm-modal-buttons">
          <button class="confirm-cancel">Отмена</button>
          <button class="confirm-delete">Удалить</button>
        </div>
      </div>
    `;
    
    document.body.appendChild(modal);
    
    modal.querySelector('.confirm-cancel').addEventListener('click', () => {
      modal.remove();
    });
    
    modal.querySelector('.confirm-delete').addEventListener('click', () => {
      // Анимация удаления
      review.style.opacity = '0';
      review.style.transform = 'scale(0.9)';
      review.style.transition = 'all 0.3s ease';
      
      setTimeout(() => {
        review.remove();
        reinitializeReviews(); // Полная переинициализация после удаления
        modal.remove();
      }, 300);
    });
    
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.remove();
      }
    });
  }
  
  // Обработчики событий
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('review_btn')) {
      const review = e.target.closest('.review_box');
      createConfirmModal(review);
    }
  });
  
  if (showMoreBtn) {
    showMoreBtn.addEventListener('click', showMoreReviews);
  }
  
  // Инициализация
  setupBanTimers();
  reinitializeReviews();
});