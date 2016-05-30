var myModule = (function(){

//Инициализирует наш модуль
  var init = function(){
    var submitform = $('.login-form').html();
    console.log(submitform);
    _setUpListeners();
    //то, что должно произойти сразу
    };

//Прослушивает события
  var _setUpListeners=function(submitform){
    $('.login-form').on('submit', _popupSubmit);
    $('.modal-content-close').on('click', _returnHtml)
    };

//Работа c функциями
  var _popupSubmit = function(e){
    e.preventDefault();//сброс начальных настроек объекта события
    $(this).html('Спасибо за отклик, мы свяжемся с вами');

  };
  var _returnHtml = function(){
    $('.login-form').html('<input type="text" class="input name" name="login" placeholder="Ваше имя" required=""><input type="text" class="input number" name="number" placeholder="Ваш номер телефона" required=""><button type="submit" class="btn">Жду звонка!</button>');
  };


//Возвращаем объект(публичные методы)
  return {
    init:init,
  };
})();
myModule.init();