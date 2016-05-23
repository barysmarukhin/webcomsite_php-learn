var HeaderChange = (function(){

//Инициализирует наш модуль
  var init = function(){
    _setUpListeners();
    //то, что должно произойти сразу
    };

//Прослушивает события
  var _setUpListeners=function(){
    $(window).on('scroll', _headerScroll);
    $('.menu').on('click', _headerMobileToggle);
    };

//Замена хедера на фиксированный хедер при скролле
  var _headerScroll = function(){
    var header = $(".header");
    var mainnav = $("#mainnav");
    var scrollnav = $("#scrollnav");
    var mainGallery = $("#maingallery");
    mainnav.addClass("displaynone");
    scrollnav.removeClass("displaynone");
    header.addClass("fixed");
    mainGallery.addClass("marginGallery");
    if ($("body").scrollTop() === 0) {
      scrollnav.addClass("displaynone");
      header.removeClass("fixed");
      mainnav.removeClass("displaynone");
      mainGallery.removeClass("marginGallery");
    }
  };

//Сворачивание или разворачивание мобильной навигации при клике
  var _headerMobileToggle = function(e){
    e.preventDefault();
    $('.mobilenav__list').toggleClass("displayblock");
  }


//Возвращаем объект(публичные методы)
  return {
    init:init,
  };
})();
HeaderChange.init();