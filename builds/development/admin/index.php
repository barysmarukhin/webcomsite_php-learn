<?php
require_once("../includes/initialize.php");

if($session->is_logged_in()) {
  redirect_to("reviews.php");
}

// Необходимо, чтобы при отправке данных, тег input с типом submit имел атрибут name="submit"!
if (isset($_POST['submit'])) { // Форма была отправлена, данные в массивe POST существуют.

  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  
  // Проверяем существование комбинации username/password.
	$found_user = User::authenticate($username, $password);//На выходе имеем cтрочку таблицы с информацией о зарегистрированном пользователе
	
  if ($found_user) {
    $session->login($found_user);
		//log_action('Login', "{$found_user->username} logged in.");
    redirect_to("reviews.php");
  } else {
    // username/password комбинация не найдена в базе данных
    $message = "Username/password - комбинация не верна.";
  }
  
} else { // Форма не была отправлена.
  $username = "";
  $password = "";
}

?>

<!doctype html>
<html lang="ru-Ru">
<head>
  <meta charset="UTF-8">
  <title>Webcom test site</title>
  <link rel="stylesheet" href="../css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body>
  <div class="wrapper">
    <header class="header">
      <nav id="mainnav" class="mainnav container">
        <a href="../index.php" class="header__logo" style="margin-top: 0;">
          <img src="../images/logo.png" alt="Webcom-media logo">
        </a>
      </nav>
    </header>
    <main>
      <h2 style="padding:50px 0; text-align: center; text-transform: uppercase; font-weight: 800;">Войдите в систему</h2>
      <?php echo output_message($message); ?>
      <form action="index.php" method="post" style="padding:50px 0; text-align: center;width:300px; margin:0 auto;">
        <table>
          <tr>
            <td>Логин:</td>
            <td>
              <input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
            </td>
          </tr>
          <tr>
            <td>Пароль:</td>
            <td>
              <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" name="submit" value="Войти" />
            </td>
          </tr>
        </table>
      </form>
      <footer class="footer" id="footer">
        <div class="footer__top">
          <article class="container">
            <div class="footer__top-logo">
              <div class="wrapper-img">
                <img class="footer__top-img" src="../images/logo.png" alt="logo">
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