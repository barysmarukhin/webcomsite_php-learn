$(document).ready(function(){
  $('#maingallery__list').bxSlider({
    auto: true
  });
  $('.review__wrapper').bxSlider({
    auto: true,
    pager: false
  });
});


// $(function() {
//     var navLi = $('.nav_right li');
//     var mainNav = $('a.nav');
//     $('.tracked').waypoint(function () {
//         var hash = $(this).attr('id');
//         var activeItem = $(this).attr('data-link');
//         navLi.removeClass('activve');
//         mainNav.removeClass('active');
//         $.each(navLi, function () {
//             if ($(this).children('a').attr('href').slice(1) == hash) {
//                 $(this).addClass('activve');

//             }
//         });
//         $.each(mainNav, function () {
//             if ($(this).attr('data-link') == activeItem) {
//                 $(this).addClass('active');

//             }

//         });
//     }, {offset: '30%'});

//     $('a.nav').click(function() {

//         var sizeScrollHeader = 80;

//         var scrolling = $(this).attr('data-link');
//         if($(window).scrollTop() > 0 && $(window).width()>995){
//             sizeScrollHeader = 120;
//         }
//         if($(window).width()<=995){
//             sizeScrollHeader = 70;
//         }

//         $('html, body').animate({
//             scrollTop:  $('#'+scrolling).offset().top-sizeScrollHeader+"px";
//         }, 1000 );

//         return false;
//         }
//     );
// }