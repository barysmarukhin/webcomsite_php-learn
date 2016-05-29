<?php require_once("includes/initialize.php"); ?>
<?php
  session_start();//создаем сессию для капчи
  $max_file_size = 1048576;   // expressed in bytes
                              //     10240 =  10 KB
                              //    102400 = 100 KB
                              //   1048576 =   1 MB
                              //  10485760 =  10 MB

  if(isset($_POST['submit'])) {//Если нажата кнопка отправки комментария, и в массив POST попали данные
    $author = trim($_POST['author']);
    $email = trim($_POST['email']);
    $status = trim($_POST['status']);
    $generatedfilename = Review::generate_name($_FILES['file_upload']['name'], $generatedfilename->upload_dir);//Генерируем новое имя файла, разрешая загрузку файлов с именем в кириллице
    $filename = basename($generatedfilename);
    $body = trim($_POST['body']);
    $captchamessage = '';//Пустое значение переменной, которая заполняется в случае ошибки с капчей
    $new_review = new Review;//Создаем экземпляр класса Review
    if($new_review->make($filename, $email, $status, $author, $body)){
      if($new_review->save() && ($_POST['captcha']==$_SESSION['randStr'])) {
        // Отзыв сохранен
        // Важно!  После отправки, Вы можете загрузить эту же страницу. 
        // Но тогда при перезагрузке страницы, форма попытается
        // повторно отправить отзыв. Поэтому делаем перенаправление:
        $session->message("Отзыв успешно размещен.");//Сохраняем сообщение в сессию, чтобы оно выводилось после перенаправления
        redirect_to("index.php#sendreview");
      } else{
        $message = "Не удалось сохранить отзыв, попробуйте еще раз";
      }
    } else{
      // Ошибка
      if ($_POST['captcha']!=$_SESSION['randStr']) {
        $captchamessage = "Неправильно введен код картинки";
      }
      if (!$_SESSION['randStr']){
        $captchamessage = "Невозможно показать картинку с кодом. Включите графику на сайте.";
      }
      $message = join("<br />", $new_review->errors);//Выводим ошибки валидации из массива, наполняемого в файле review.php
    } 

  } else {
    $author = "";
    $status = "";
    $body = "";
  }


  $reviews = Review::find_all();
?>
<!doctype html>
<html lang="ru-Ru">
<head>
  <meta charset="UTF-8">
  <title>Webcom test site</title>
  <link rel="stylesheet" href="css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body>
  <div class="wrapper">
    <!-- POINTS -->
    <ul class="nav_right displaynone" id="scroller">
      <li class="nav_right__item maingallery__item">
          <a href="#maingallery" data-link="maingallery" class="nav_right__link"></a>
      </li>
      <li class="nav_right__item about__item">
          <a href="#about" data-link="about" class="nav_right__link"></a>
      </li>
      <li class="nav_right__item works__item">
          <a href="#works" data-link="works" class="nav_right__link"></a>
      </li>
      <li class="nav_right__item whyus__item">
          <a href="#whyus" data-link="whyus" class="nav_right__link"></a>
      </li>
      <li class="nav_right__item howwork__item">
          <a href="#howwork" data-link="howwork" class="nav_right__link"></a>
      </li>
      <li class="nav_right__item review__item">
          <a href="#review" data-link="review" class="nav_right__link"></a>
      </li>
      <li class="nav_right__item sendreview__item">
          <a href="#sendreview" data-link="sendreview" class="nav_right__link"></a>
      </li>
    </ul>
    <!-- SITE CONTENT -->
    <header class="header">
      <nav id="mainnav" class="mainnav container">
        <a href="index.php" class="header__logo">
          <img src="images/logo.png" alt="Webcom-media logo">
        </a>
        <ul class="header__list">
          <li class="header__list-item">
            <a href="#maingallery" class="header__list-link active">Главная</a>
          </li>
          <li class="header__list-item">
            <a href="#works" class="header__list-link">Портфолио</a>
          </li>
          <li class="header__list-item">
            <a href="#whyus" class="header__list-link">Преимущества</a>
          </li>
          <li class="header__list-item">
            <a href="#howwork" class="header__list-link">Схема работы</a>
          </li>
          <li class="header__list-item">
            <a href="#review" class="header__list-link">Отзывы</a>
          </li>
          <li class="header__list-item">
            <a href="#sendreview" class="header__list-link">Контакты</a>
          </li>
        </ul>
        <div class="header__phones">
          <p class="header__phones-number">+375 (29) 123-45-67</p>
          <p class="header__phones-number">+375 (33) 123-45-67</p>
        </div>
      </nav>
      <nav id="scrollnav" class="scrollnav displaynone container">
        <a href="index.php" class="header__logo scrollLogo">
          <img src="images/logo.png" alt="Webcom-media logo">
        </a>
        <ul class="header__list">
          <li class="header__list-item">
            <a href="#maingallery" class="header__list-link scrollLink active">Главная</a>
          </li>
          <li class="header__list-item">
            <a href="#works" class="header__list-link scrollLink">Портфолио</a>
          </li>
          <li class="header__list-item">
            <a href="#whyus" class="header__list-link scrollLink">Преимущества</a>
          </li>
          <li class="header__list-item">
            <a href="#howwork" class="header__list-link scrollLink">Схема работы</a>
          </li>
          <li class="header__list-item">
            <a href="#review" class="header__list-link scrollLink">Отзывы</a>
          </li>
          <li class="header__list-item">
            <a href="#sendreview" class="header__list-link scrollLink">Контакты</a>
          </li>
        </ul>
          <a href="#" id="callback" class="requestLink">Оставить заявку</a>
      </nav>
      <nav id="mobilenav" class="mobilenav container"><span class="mobile-trigger"></span>
        <div class="mobilenav__wrapper">
          <a href="index.php" class="header__logo scrollLogo">
            <img src="images/logo.png" alt="Webcom-media logo">
          </a>
          <div class="mobilenav__buttons">
            <img class="mobilenav__buttons-img search" src="images/mobilesearch.png" alt="search button">
            <img class="mobilenav__buttons-img menu" src="images/mobilemenu.png" alt="search button">
          </div>
        </div>
        <ul class="mobilenav__list">
          <li class="mobilenav__list-item"><a href="#maingallery" class="mobilenav__list-link active">Главная</a></li>
          <li class="mobilenav__list-item"><a href="#works" class="mobilenav__list-link">Портфолио</a></li>
          <li class="mobilenav__list-item"><a href="#howwork" class="mobilenav__list-link">Преимущества</a></li>
          <li class="mobilenav__list-item"><a href="#review" class="mobilenav__list-link">Отзывы</a></li>
          <li class="mobilenav__list-item"><a href="#sendreview" class="mobilenav__list-link">Контакты</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <div class="maingallery" data-link="maingallery" id="maingallery">
        <article class="container">
          <ul id="maingallery__list" class="maingallery__list">
            <li class="maingallery__list-item">
              <div class="maingallery__list-container">
                <h1 class="maingallery__header">Главный заголовок</h1>
                <p class="maingallery_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et doloremagna aliqua.</p>
                <a href="#" id="callback" class="maingallery__link">Оформить заявку</a>
              </div>
            </li>
            <li class="maingallery__list-item">
              <div class="maingallery__list-container">
                <h1 class="maingallery__header">Второй заголовок</h1>
                <p class="maingallery_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et doloremagna aliqua.</p>
                <a href="#" id="callback" class="maingallery__link">Оформить заявку</a>
              </div>
            </li>
            <li class="maingallery__list-item">
              <div class="maingallery__list-container">
                <h1 class="maingallery__header">Третий заголовок</h1>
                <p class="maingallery_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et doloremagna aliqua.</p>
                <a href="#" id="callback" class="maingallery__link">Оформить заявку</a>
              </div>
            </li>
            <li class="maingallery__list-item">
              <div class="maingallery__list-container">
                <h1 class="maingallery__header">Четвертый заголовок</h1>
                <p class="maingallery_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et doloremagna aliqua.</p>
                <a href="#" id="callback" class="maingallery__link">Оформить заявку</a>
              </div>
            </li>
            <li class="maingallery__list-item">
              <div class="maingallery__list-container">
                <h1 class="maingallery__header">Пятый заголовок</h1>
                <p class="maingallery_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et doloremagna aliqua.</p>
                <a href="#" id="callback" class="maingallery__link">Оформить заявку</a>
              </div>
            </li>
          </ul>
          <a href="#about" class="maingallery__scrolldown">scrolldown text</a>
        </article>
      </div>
      <div class="about " data-link="about" id="about">
        <article class="container">
          <h2 class="about__header">О компании</h2>
          <p class="about__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut.</p>
          <ul class="about__list">
            <li class="about__list-item">
              <a href="#" class="about__list-link"><img src="images/imageBlockFirst.png" alt="About company feature"></a>
            </li><!-- 
             --><li class="about__list-item">
              <a href="#" class="about__list-link"><img src="images/imageBlockFirst.png" alt="About company feature"></a>
            </li><!-- 
             --><li class="about__list-item">
              <a href="#" class="about__list-link"><img src="images/imageBlockFirst.png" alt="About company feature"></a>
            </li>
          </ul>
        </article>
      </div>
      <div class="works " data-link="works" id="works">
        <article class="container">
          <h2 class="works__header">Наша Работа</h2>
          <p class="works__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut.</p>
          <ul class="works__list">
            <li class="works__list-item">
              <a href="#" class="works__list-link">
                <img src="images/imageBlockSecond.png" alt="Our works feature">
                <div class="works__list-content">
                  <h4 class="works__list-header">Название работы</h4>
                  <p class="works__list-text">Описание работы</p>
                </div> 
              </a>
            </li><!-- 
             --><li class="works__list-item">
              <a href="#" class="works__list-link">
                <img src="images/imageBlockSecond.png" alt="Our works feature">
                <div class="works__list-content">
                  <h4 class="works__list-header">Название работы</h4>
                  <p class="works__list-text">Описание работы</p>
                </div>
              </a>
            </li><!-- 
             --><li class="works__list-item">
              <a href="#" class="works__list-link">
                <img src="images/imageBlockSecond.png" alt="Our works feature">
                <div class="works__list-content">
                  <h4 class="works__list-header">Название работы</h4>
                  <p class="works__list-text">Описание работы</p>
                </div>
              </a>
            </li><!-- 
             --><li class="works__list-item">
              <a href="#" class="works__list-link">
                <img src="images/imageBlockSecond.png" alt="Our works feature">
                <div class="works__list-content">
                  <h4 class="works__list-header">Название работы</h4>
                  <p class="works__list-text">Описание работы</p>
                </div>
              </a>
            </li><!-- 
             --><li class="works__list-item">
              <a href="#" class="works__list-link">
                <img src="images/imageBlockSecond.png" alt="Our works feature">
                <div class="works__list-content">
                  <h4 class="works__list-header">Название работы</h4>
                  <p class="works__list-text">Описание работы</p>
                </div>
              </a>
            </li><!-- 
             --><li class="works__list-item">
              <a href="#" class="works__list-link">
                <img src="images/imageBlockSecond.png" alt="Our works feature">
                <div class="works__list-content">
                  <h4 class="works__list-header">Название работы</h4>
                  <p class="works__list-text">Описание работы</p>
                </div>
              </a>
            </li>
          </ul>
        </article>
      </div>
      <div class="calltoaction " data-link="calltoaction" id="calltoaction">
        <article class="container">
          <h3 class="calltoaction__header">Не упусти свой шанс! Оформи заказ прямо сейчас!</h3>
          <!-- <input id="callback" class="calltoaction__button" type="button" value="Оформить заказ"> -->
          <a href="#" id="callback" class="calltoaction__button">Оформить заказ</a>     
        </article>
      </div>
      <div class="whyus " data-link="whyus" id="whyus">
        <article class="container">
          <h2 class="whyus__header">Почему мы</h2>
          <p class="works__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut.</p>
          <ul class="whyus__list">
            <li class="whyus__list-item">
            <img src="images/features/monitor.png" alt="advantage">
              <h4 class="whyus__list-header">Преимущество 1</h4>
              <p class="whyus__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="whyus__list-item">
            <img src="images/features/magic.png" alt="advantage">
              <h4 class="whyus__list-header">Преимущество 2</h4>
              <p class="whyus__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="whyus__list-item">
            <img src="images/features/rocket.png" alt="advantage">
              <h4 class="whyus__list-header">Преимущество 3</h4>
              <p class="whyus__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="whyus__list-item">
            <img src="images/features/globe.png" alt="advantage">
              <h4 class="whyus__list-header">Преимущество 4</h4>
              <p class="whyus__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="whyus__list-item">
            <img src="images/features/settings.png" alt="advantage">
              <h4 class="whyus__list-header">Преимущество 5</h4>
              <p class="whyus__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="whyus__list-item">
            <img src="images/features/layers.png" alt="advantage">
              <h4 class="whyus__list-header">Преимущество 6</h4>
              <p class="whyus__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="whyus__list-item">
            <img src="images/features/wrench.png" alt="advantage">
              <h4 class="whyus__list-header">Преимущество 7</h4>
              <p class="whyus__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="whyus__list-item">
            <img src="images/features/note.png" alt="advantage">
              <h4 class="whyus__list-header">Преимущество 8</h4>
              <p class="whyus__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li>
          </ul>
        </article>
      </div>
      <div class="howwork " data-link="howwork" id="howwork">
        <article class="container">
          <h2 class="howwork__header">Как мы работаем</h2>
          <p class="howwork__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut.</p>
          <ul class="howwork__list">
            <li class="howwork__list-item">
              <div class="howwork__list-image">
                <img src="images/imageBlockThird.png" alt="working stage">
                <div class="howwork__list-number">1</div>
              </div>
              <h4 class="howwork__list-header">Этап 1</h4>
              <p class="howwork__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="howwork__list-item">
              <div class="howwork__list-image">
                <img src="images/imageBlockThird.png" alt="working stage">
                <div class="howwork__list-number">2</div>
              </div>
              <h4 class="howwork__list-header">Этап 2</h4>
              <p class="howwork__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="howwork__list-item">
              <div class="howwork__list-image">
                <img src="images/imageBlockThird.png" alt="working stage">
                <div class="howwork__list-number">3</div>
              </div>
              <h4 class="howwork__list-header">Этап 3</h4>
              <p class="howwork__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="howwork__list-item">
              <div class="howwork__list-image">
                <img src="images/imageBlockThird.png" alt="working stage">
                <div class="howwork__list-number">4</div>
              </div>
              <h4 class="howwork__list-header">Этап 4</h4>
              <p class="howwork__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="howwork__list-item">
              <div class="howwork__list-image">
                <img src="images/imageBlockThird.png" alt="working stage">
                <div class="howwork__list-number">5</div>
              </div>
              <h4 class="howwork__list-header">Этап 5</h4>
              <p class="howwork__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li><!-- 
             --><li class="howwork__list-item">
              <div class="howwork__list-image">
                <img src="images/imageBlockThird.png" alt="working stage">
                <div class="howwork__list-number">6</div>
              </div>
              <h4 class="howwork__list-header">Этап 6</h4>
              <p class="howwork__list-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
            </li>
          </ul>
        </article>
      </div>
      <div class="review " data-link="review" id="review">
        <article class="container">
          <h2 class="review__header">Отзывы о нашей компании</h2>
          <ul class="review__wrapper">
            <!-- Запускаем цикл для каждой строчки таблицы $reviews -->
            <?php foreach($reviews as $review): ?>
              <li class="review__slide">
                <div class="review__container">
                  <div class="review__imgwrapper">
                    <img src="<?php echo $review->image_path(); ?>" alt="photo person" class="review__photo">
                  </div>
                  <div class="review__textwrapper">
                    <p class="review__textwrapper-name"><span><?php echo htmlentities($review->author); ?>,</span> <?php echo htmlentities($review->status); ?></p>
                    <p class="review__textwrapper-message"><?php echo strip_tags($review->body, '<strong><em><p>'); ?>.</p>
                    <a href="reviews.php" class="review__textwrapper-link">Все отзывы</a>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>      
        </article>
      </div>
      <div class="sendreview " data-link="sendreview" id="sendreview">
        <article class="container">
          <h2 class="sendreview__header">Оставьте отзыв</h2>
          <p class="works__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut.</p>
          <div class="sendreview__contacts">
            <h3 class="sendreview__contacts-header">Наш адрес</h3>
            <p class="sendreview__contacts-text">
              г.Минск, ул.Скрыганова, 6А, 4 этаж
            </p>
            <h3 class="sendreview__contacts-header">Звоните</h3>
            <p class="sendreview__contacts-text">
              +375 (29) 123-45-67, +375 (33) 123-45-67
            </p>
            <h3 class="sendreview__contacts-header">Пишите</h3>
            <p class="sendreview__contacts-text">
              info@promo-webcom.by
            </p>
          </div>
          <form action="index.php" method="POST" enctype="multipart/form-data" class="sendreview__sendform">
            <?php echo output_message($message)."</br>";?>
            <?php echo output_message($captchamessage)."</br>";?>            
            <label class="sendreview__sendform-label" for="name">
            <h3 class="sendreview__sendform-header">Ваше имя</h3>
            <input class="sendreview__sendform-input" type="text" name="author" id="name" placeholder="Иванов Иван" required value="<?php echo $author;?>">
            </label>
            <label class="sendreview__sendform-label" for="email">
              <h3 class="sendreview__sendform-header">e-mail</h3>
              <input class="sendreview__sendform-input" type="email" name="email" id="email" placeholder="example@mysite.com" value="<?php echo $email; ?>">
            </label>
            <label class="sendreview__sendform-label" for="position">
              <h3 class="sendreview__sendform-header">Ваша должность</h3>
              <input class="sendreview__sendform-input" type="text" name="status" id="position" placeholder="Должность" value="<?php echo $status; ?>">
            </label>
            <label class="sendreview__sendform-label" for="reviewLabel">
              <h3 class="sendreview__sendform-header">Отзыв</h3>
              <textarea class="sendreview__sendform-textarea" name="body" rows="5" name="reviewLabel" id="reviewLabel" placeholder="Ваше сообщение" required><?php echo $body; ?></textarea> 
            </label>        
            <div class="sendreview__sendform-addphoto"> 
              <label class="sendreview__sendform-uploadlabel">+
                <input id="fileupload" type="file" name="file_upload" class="sendreview__sendform-fileupload">
                <input id="fileurl" type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" class="endreview__sendform-fileurl">
              </label>
              <input id="filename" name="filename" class="sendreview__sendform-filename">
              <span class="sendreview__sendform-span">Фото</span>
            </div>
            <div class="sendreview__sendform-capcha">
              <div class="sendreview__sendorm-container_capcha">
                <label class="sendreview__sendform-label" for="capcha">
                <h3 class="sendreview__sendform-header">Введите код с картинки</h3>
                <input class="sendreview__sendform-input" type="text" name="captcha" size="6" id="capcha">
                <div class="sendreview__sendform-picture">
                  <img src="captcha/noise-picture.php" alt="captcha">
                </div>
              </label>
              </div>
              <div class="sendreview__sendform-container_submit">
                <input type="submit" name="submit" class="sendreview__sendform-submit" value="Отправить">
              </div>  
            </div>     
          </form>
        </article>
        
      </div>
    </main>
  </div>
  <footer class="footer" id="footer">
    <div class="footer__top">
      <article class="container">
        <div class="footer__top-logo">
          <div class="wrapper-img">
            <img class="footer__top-img" src="images/logo.png" alt="logo">
          </div>
          <p class="footer__top-text">Дисклеймер компании</p>
        </div>
        <div class="footer__top-social">
          <h3 class="footer__top-header">Мы в соцсетях</h3> 
          <ul class="social__list">
            <li class="social__list-item"><a href="#" class="social__list-link instagram">instagram</a></li>
            <li class="social__list-item"><a href="#" class="social__list-link twitter">twitter</a></li>
            <li class="social__list-item"><a href="#" class="social__list-link facebook">facebook</a></li>
          </ul>   
        </div>
        <div class="footer__top-address">
          <h3 class="footer__top-header">Адрес</h3>
          <p class="footer__top-text">г. Минск, ул. Скрыганова, 6А, 4 этаж</p>
        </div>
      </article>
    </div>
    <div class="footer__bottom">
      <arcticle class="container">
        <p class="footer__bottom-text">Copyrights 2015 &#169; Webcom Media </p>
      </arcticle>
    </div>
  </footer>
  <a href="#maingallery" class="scrolltop displaynone">На верх страницы</a>
  <div class="modal-content">
    <div class="modal-content-close" title="Закрыть">Закрыть</div>
    <h2 class="modal-content-title">Обратный звонок</h2>
    <p>Введите пожалуйста свое имя и номер телефона.</p>
    <form class="login-form" action="/echo" method="post">
      <input type="text" class="input name" name="login" placeholder="Ваше имя" required>
      <input type="text" class="input number" name="number" placeholder="Ваш номер телефона" required>
      <button type="submit" class="btn">Жду звонка!</button>
    </form>
  </div><!--modal-content-->
  <script src="js/script.js"></script>
</body>
</html>
