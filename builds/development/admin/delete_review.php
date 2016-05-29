<?php require_once("../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("index.php"); } ?>
<?php
  // У записи должен быть id
  if(empty($_GET['id'])) {
    $session->message("Id записи не был определен.");
    redirect_to('reviews.php');
  }

  $review = Review::find_by_id($_GET['id']);
  if ($review->filename != "photoPerson.png"){
    if($review && $review->destroy()) {
    $session->message("Отзыв был удален.");
    redirect_to("reviews.php");
    } else {
      $session->message("Отзыв не удалился, попробуйте еще раз.");
      redirect_to('reviews.php');
    }
  } else{
    if($review && $review->delete()) {
    $session->message("Отзыв был удален.");
    redirect_to("reviews.php");
    } else {
      $session->message("Отзыв не удалился, попробуйте еще раз.");
      redirect_to('reviews.php');
    }
  }
  
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>

