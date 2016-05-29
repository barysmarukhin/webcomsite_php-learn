<?php
// Здесь класс, обеспечивающий работу с SESSIONS
// В нашем случае, это управление авторизацией пользователя

// Помните, что при работе с сессиями, крайне нежелательно
// в сейссиях размещение объектов, связанных с базами данных

class Session {
  
  private $logged_in=false;
  public $user_id;
  public $message;
  public $first_name;
  
  function __construct() {
    session_start();
    $this->check_message();
    $this->check_login();
    if($this->logged_in) {
      // Действия, если пользователь зарегистрирован
    } else {
      // Действия, если пользователь не зарегистрирован
    }
  }
  
  public function is_logged_in() {
    return $this->logged_in;
  }

  public function login($user) {
    // поиск в базе данных искомой комбинации "имя пользователя/пароль""
    if($user){
      $this->user_id = $_SESSION['user_id'] = $user->id;
      $this->first_name = $_SESSION['first_name'] = $user->first_name;
      $this->logged_in = true;
    }
  }
  
  public function logout() {
    unset($_SESSION['user_id']);
    unset($this->user_id);
    unset($this->first_name);
    $this->logged_in = false;
  }

  public function message($msg="") {
    if(!empty($msg)) {
      // если переменная не пуста, то "сохраняем сообщение"
      $_SESSION['message'] = $msg;
    } else {
      // иначе "извлекаем сообщение"
      return $this->message;
    }
  }

  private function check_login() {
    if(isset($_SESSION['user_id'])) {
      $this->user_id = $_SESSION['user_id'];
      $this->first_name = $_SESSION['first_name'];
      $this->logged_in = true;
    } else {
      unset($this->user_id);
      $this->logged_in = false;
    }
  }
  
  private function check_message() {
    // Размещено ли сообщение в сессии?
    if(isset($_SESSION['message'])) {
      // Добавляем атрибут, и удаляем размещенную версию
      $this->message = $_SESSION['message'];
      unset($_SESSION['message']);
    } else {
      $this->message = "";
    }
  }
  
}

$session = new Session();
$message = $session->message();

?>