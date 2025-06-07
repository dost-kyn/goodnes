//   перелистывание карточек
document.addEventListener('DOMContentLoaded', function () {
  const cardsContainer = document.querySelector('.recipes_cards');
  const prevBtn = document.querySelector('.pre');
  const nextBtn = document.querySelector('.next');
  const cardWidth = 270; // Ширина одной карточки
  const gap = 45; // Расстояние между карточками

  function updateButtons() {
    const maxScroll = cardsContainer.scrollWidth - cardsContainer.clientWidth;

    // Всегда показываем кнопки, но меняем их прозрачность
    prevBtn.style.opacity = cardsContainer.scrollLeft > 10 ? '1' : '0.5';
    nextBtn.style.opacity = cardsContainer.scrollLeft < maxScroll - 10 ? '1' : '0.5';

    prevBtn.style.pointerEvents = cardsContainer.scrollLeft > 10 ? 'all' : 'none';
    nextBtn.style.pointerEvents = cardsContainer.scrollLeft < maxScroll - 10 ? 'all' : 'none';
  }

  nextBtn.addEventListener('click', () => {
    cardsContainer.scrollBy({
      left: cardWidth + gap,
      behavior: 'smooth'
    });
  });

  prevBtn.addEventListener('click', () => {
    cardsContainer.scrollBy({
      left: -(cardWidth + gap),
      behavior: 'smooth'
    });
  });

  cardsContainer.addEventListener('scroll', updateButtons);

  // Инициализация при загрузке
  updateButtons();

  // Обновляем при изменении размера окна
  window.addEventListener('resize', updateButtons);
});


// document.addEventListener('DOMContentLoaded', function() {
//   const cardsContainer = document.querySelector('.recipes_cards');
//   const prevBtn = document.querySelector('.pre');
//   const nextBtn = document.querySelector('.next');
//   const cardWidth = 270; // Ширина одной карточки
//   const gap = 45; // Расстояние между карточками

//   // Прокрутка вперед
//   nextBtn.addEventListener('click', () => {
//     cardsContainer.scrollBy({
//       left: cardWidth + gap,
//       behavior: 'smooth'
//     });
//   });

//   // Прокрутка назад
//   prevBtn.addEventListener('click', () => {
//     cardsContainer.scrollBy({
//       left: -(cardWidth + gap),
//       behavior: 'smooth'
//     });
//   });

//   // Скрываем кнопки в начале/конце
//   cardsContainer.addEventListener('scroll', () => {
//     const maxScroll = cardsContainer.scrollWidth - cardsContainer.clientWidth;

//     prevBtn.style.visibility = cardsContainer.scrollLeft > 10 ? 'visible' : 'hidden';
//     nextBtn.style.visibility = cardsContainer.scrollLeft < maxScroll - 10 ? 'visible' : 'hidden';
//   });

//   // Инициализация видимости кнопок
//   cardsContainer.dispatchEvent(new Event('scroll'));
// });






// Код для главной страницы меню для каталога
document.addEventListener('DOMContentLoaded', function () {
  const menuButtons = document.querySelectorAll('.menu_btn');

  // Создаем соответствие между названиями кнопок и ID чекбоксов
  const categoryMapping = {
    'торты': 'wr1',
    'печенье': 'wr2',
    'пироги': 'wr3',
    'кексы': 'wr4',
    'конфеты': 'wr5',
    'хлеб': 'wr6'
  };

  // Добавляем обработчики для кнопок меню
  menuButtons.forEach(button => {
    button.addEventListener('click', function () {
      const categoryName = this.querySelector('.menu_btn_name').textContent.toLowerCase();
      const checkboxId = categoryMapping[categoryName];

      // Перенаправляем на страницу каталога с параметром категории
      window.location.href = `catalog.php?category=${checkboxId}`;
    });
  });
});











//проверка на подписку
document.getElementById('subscriptionForm').addEventListener('submit', function (e) {
  e.preventDefault(); // Предотвращаем стандартную отправку формы

  const emailInput = this.querySelector('.subscription_inp');
  const checkbox = document.getElementById('wr21');
  let isValid = true;

  // Проверка email
  if (!emailInput.value.trim()) {
    emailInput.style.borderColor = 'red';
    isValid = false;
  } else if (!validateEmail(emailInput.value.trim())) {
    emailInput.style.borderColor = 'red';
    alert('Пожалуйста, введите корректный email');
    isValid = false;
  } else {
    emailInput.style.borderColor = '';
  }

  // Проверка чекбокса
  if (!checkbox.checked) {
    alert('Пожалуйста, подтвердите согласие на обработку данных');
    isValid = false;
  }

  // Если все проверки пройдены
  if (isValid) {
    // Здесь можно добавить AJAX-запрос для отправки данных
    alert('Спасибо за подписку!');
    this.reset(); // Очищаем форму после успешной отправки
  }
});

// Функция валидации email
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}