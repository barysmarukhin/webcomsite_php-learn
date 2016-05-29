<?php require_once("includes/initialize.php");
  $reviews = Review::find_all();

  // 1. узнаем номер текущей страницы ($current_page)
  $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

  // 2. указываем количество записей на странице ($per_page)
  $per_page = 3;

  // 3. считаем количество всех записей ($total_count)
  $total_count = Review::count_all();
  //exit($total_count);

  // Ищем все отзывы,
  // используя пагинацию, вместо записи
  //$reviews = Review::find_all();
  
  $pagination = new Pagination($page, $per_page, $total_count);
  
  // Instead of finding all records, just find the records 
  // for this page
  $sql = "SELECT * FROM reviews ";
  $sql .= "LIMIT {$per_page} ";
  $sql .= "OFFSET {$pagination->offset()}";
  $reviews = Review::find_by_sql($sql);
  
  // Need to add ?page=$page to all links we want to 
  // maintain the current page (or store $page in $session)
  
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
    <header class="header">
      <nav id="mainnav" class="mainnav container">
        <a href="index.php" class="header__logo" style="margin-top: 0;">
          <img src="images/logo.png" alt="Webcom-media logo">
        </a>
      </nav>
    </header>
    <main>
      <div class="review" data-link="review" id="review">
        <article class="container">
          <h2 class="review__header">Отзывы о нашей компании</h2>
          <ul class="review__wrapper">
            <?php foreach($reviews as $review): ?>
              <li class="review__slide">
                <div class="review__container">
                  <div class="review__imgwrapper">
                    <img src="<?php echo $review->image_path(); ?>" alt="photo person" class="review__photo">
                  </div>
                  <div class="review__textwrapper">
                    <p class="review__textwrapper-name"><span><?php echo htmlentities($review->author); ?>,</span> <?php echo htmlentities($review->status); ?></p>
                    <p class="review__textwrapper-message"><?php echo strip_tags($review->body, '<strong><em><p>'); ?>.</p>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
          <!-- Show pagination -->
          <div id="pagination" style="text-align: center;clear: both;padding-bottom: 10px;">
            <?php
              if($pagination->total_pages() > 1) {
                
                if($pagination->has_previous_page()) { 
                  echo "<a style=\"color:#fff\" href=\"reviews.php?page=";
                  echo $pagination->previous_page();
                  echo "\">&laquo; Назад</a> "; 
                }

                for($i=1; $i <= $pagination->total_pages(); $i++) {
                  if($i == $page) {
                    echo " <span>{$i}</span> ";
                  } else {
                    echo " <a style=\"color:#fff\" href=\"reviews.php?page={$i}\">{$i}</a> "; 
                  }
                }

                if($pagination->has_next_page()) { 
                  echo " <a style=\"color:#fff\" href=\"reviews.php?page=";
                  echo $pagination->next_page();
                  echo "\">Вперед &raquo;</a> "; 
                }                
              }
            ?>
          </div>  
        </article>
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
    </main>
  </div>
</body>
</html>
