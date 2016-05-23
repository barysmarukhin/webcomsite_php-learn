$(function() {
  var topoffset = 82;

  //var isTouch = 'ontouchstart' in document.documentElement;


//Animated Scrolling
  $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top-topoffset
        }, 1000);
        return false;
      }//target.lenght
    }//location hostname
  });//on click

//Highlight navigation
  $(window).scroll(function(){
    var windowpos = $(window).scrollTop() + topoffset;
    $('header nav li a').removeClass('active');
    $('.nav_right__item').removeClass('activve');

    if (windowpos >= $('#maingallery').offset().top){
      $('header nav li a').removeClass('active');
      $('.nav_right__item').removeClass('activve');
      $('header a[href$="#maingallery"]').addClass('active');
      $('.maingallery__item').addClass('activve');
    }//windowpos

    if (windowpos >= $('#about').offset().top){
      $('header nav li a').removeClass('active');
      $('.nav_right__item').removeClass('activve');
      $('header a[href$="#about"]').addClass('active');
      $('.about__item').addClass('activve');
    }//windowpos

    if (windowpos >= $('#works').offset().top){
      $('header nav li a').removeClass('active');
      $('.nav_right__item').removeClass('activve');
      $('header a[href$="#works"]').addClass('active');
      $('.works__item').addClass('activve');
    }//windowpos

    if (windowpos >= $('#whyus').offset().top){
      $('header nav li a').removeClass('active');
      $('.nav_right__item').removeClass('activve');
      $('header a[href$="#whyus"]').addClass('active');
      $('.whyus__item').addClass('activve');
    }//windowpos

    if (windowpos >= $('#howwork').offset().top){
      $('header nav li a').removeClass('active');
      $('.nav_right__item').removeClass('activve');
      $('header a[href$="#howwork"]').addClass('active');
      $('.howwork__item').addClass('activve');
    }//windowpos

    if (windowpos >= $('#review').offset().top){
      $('header nav li a').removeClass('active');
      $('.nav_right__item').removeClass('activve');
      $('header a[href$="#review"]').addClass('active');
      $('.review__item').addClass('activve');
    }//windowpos

    if (windowpos >= $('#sendreview').offset().top){
      $('header nav li a').removeClass('active');
      $('.nav_right__item').removeClass('activve');
      $('header a[href$="#sendreview"]').addClass('active');
      $('.sendreview__item').addClass('activve');
    }//windowpos
  });//window scroll
});//on load