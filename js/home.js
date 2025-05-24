//   перелистывание карточек
document.addEventListener('DOMContentLoaded', function() {
  const cardsContainer = document.querySelector('.recipes_cards');
  const prevBtn = document.querySelector('.pre');
  const nextBtn = document.querySelector('.next');
  const cardWidth = 270; // Ширина одной карточки
  const gap = 45; // Расстояние между карточками
  
  // Прокрутка вперед
  nextBtn.addEventListener('click', () => {
    cardsContainer.scrollBy({
      left: cardWidth + gap,
      behavior: 'smooth'
    });
  });
  
  // Прокрутка назад
  prevBtn.addEventListener('click', () => {
    cardsContainer.scrollBy({
      left: -(cardWidth + gap),
      behavior: 'smooth'
    });
  });
  
  // Скрываем кнопки в начале/конце
  cardsContainer.addEventListener('scroll', () => {
    const maxScroll = cardsContainer.scrollWidth - cardsContainer.clientWidth;
    
    prevBtn.style.visibility = cardsContainer.scrollLeft > 10 ? 'visible' : 'hidden';
    nextBtn.style.visibility = cardsContainer.scrollLeft < maxScroll - 10 ? 'visible' : 'hidden';
  });
  
  // Инициализация видимости кнопок
  cardsContainer.dispatchEvent(new Event('scroll'));
});





// Код для главной страницы (index.html)
document.addEventListener('DOMContentLoaded', function() {
  const menuButtons = document.querySelectorAll('.menu_btn');
  
  // Создаем соответствие между названиями кнопок и ID чекбоксов
  const categoryMapping = {
    'торты': 'wr6',
    'печенье': 'wr1',
    'пироги': 'wr2',
    'кексы': 'wr5',
    'конфеты': 'wr3',
    'хлеб': 'wr4'
  };

  // Добавляем обработчики для кнопок меню
  menuButtons.forEach(button => {
    button.addEventListener('click', function() {
      const categoryName = this.querySelector('.menu_btn_name').textContent.toLowerCase();
      const checkboxId = categoryMapping[categoryName];
      
      // Перенаправляем на страницу каталога с параметром категории
      window.location.href = `catalog.html?category=${checkboxId}`;
    });
  });
});