<?php

// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Review extends DatabaseObject {

  protected static $table_name="reviews";
  protected static $db_fields=array('id', 'filename', 'email', 'status', 'author', 'body');

  public $id;
  public $filename;
  public $status;
  public $author;
  public $body;
  public $email;
  public $size;
  public $type;

  private $temp_path;
  protected $upload_dir="images/reviews";
  public $errors=array();
  
  // "new" зарезервированное слово, поэтому здесь используем "make" (или "build")
  //Функция создания отзыва
  public function make($filename = "photoPerson.png",$email="unknown",$status="Нет статуса", $author="Неизвестный автор", $body="") {
    if(empty($author) && empty($body)) {
      $this->errors[] = "Поля \"Ваше имя\" и \"Отзыв\" обязательны к заполнению.";
      return false;
    }
      //$review = new Review();//Создаем экземпляр класса Review
    elseif(!$_FILES['file_upload']['name']) {
      // если ничего не загружено, загружаем дефолтную картинку
      //$this->errors[] = "Нет загруженного файла.";
        $filename = "photoPerson.png";
        $this->status = $status;//Статус, должность
        $this->author = $author;//Автор
        $this->email = $email;//Email
        $this->body = $body;//Текст сообщения
        return true;
    } 

    elseif(!$this->is_image($this->upload_dir.$filename)){
      $this->errors[] = "Файл должен быть картинкой";
      //var_dump($this->errors);
      // echo($filename);
      // exit();
      return false;
    }

    elseif($_FILES['file_upload']['error'] != 0) {
      // ошибка: что-то не так со стороны PHP
      $this->errors[] = $this->upload_errors[$_FILES['file_upload']['error']];
      return false;
    } 

    else{
      // if(!$filename){
      //   $filename = "photoPerson.png";
      // } else {
      //   $this->attach_file($_FILES['file_upload']);
      // }

      // if (!$this->is_image($this->upload_dir)) {
      //   $this->errors[] = "Загружаемый файл должен быть картинкой";
      //   return false;
      // }
      $this->status = $status;//Статус, должность
      $this->author = $author;//Автор
      $this->email = $email;//Email
      $this->body = $body;//Текст сообщения
      $this->temp_path = $_FILES['file_upload']['tmp_name'];//Временный путь
      $this->originfilename = basename($_FILES['file_upload']['name']);//Оригинальное имя фотографии
      $this->filename = $filename;//Сгенерированное имя файла
      // echo $this->temp_path."</br>";
      // echo $filename."</br>";
      // echo $this->originfilename."</br>";
      // var_dump($this->errors);
      // exit();
      return true;
      }
    } 
  

  protected $upload_errors = array(
    // http://www.php.net/manual/en/features.file-upload.errors.php
    UPLOAD_ERR_OK         => "Нет ошибок.",
    UPLOAD_ERR_INI_SIZE   => "Размер файла больше, чем upload_max_filesize.",
    UPLOAD_ERR_FORM_SIZE  => "Размер формы превышает 1МБ (MAX_FILE_SIZE).",
    UPLOAD_ERR_PARTIAL    => "Файл не был загружен полностью.",
    UPLOAD_ERR_NO_FILE    => "Нет файла.",
    UPLOAD_ERR_NO_TMP_DIR => "Нет временной директории.",
    UPLOAD_ERR_CANT_WRITE => "Невозможно записать на диск.",
    UPLOAD_ERR_EXTENSION  => "Загрузка файла приостановлена из-за ошибок расширения."
  );

  // public function attach_file($file) {
  //   // Выполняем проверку ошибок для формы при отсутствии выбранного файла
  //   if(!$file || empty($file) || !is_array($file)) {
  //     // если ничего не загружено, загружаем дефолтную картинку
  //     $review->errors[] = "Нет загруженного файла.";
  //     //$this->filename = "photoPerson.png";
  //     return false;
  //   }
  //   // elseif(!$review->is_image($review->upload_dir.$filename)) {
  //   //   exit($review->filename);
  //   //   $review->errors[] = "Загружаемый файл должен быть картинкой";
  //   //   return false;
  //   // }
  //   elseif($file['error'] != 0) {
  //     // ошибка: что-то не так со стороны PHP
  //     $review->errors[] = $review->upload_errors[$file['error']];
  //     return false;
  //   } else {
  //     // Устанавливаем атрибуты объекта для данных из формы
  //     $this->temp_path  = $file['tmp_name'];//Временный путь к файлу
  //     $this->filename   = basename($file['name']);//Имя файла без пути к нему
  //     $this->errors[] = "Загрузите заново фотографию";//Сообщение выводится, когда с загрузкой фотографий нет проблем, но необходимые поля формы не заполнены
  //           //exit($this->filename);
  //     // Еще не стоит беспокоиться о сохранении чего-либо в базу данных.
  //     //exit($this->temp_path);
  //     return true;
  //   }
  // }

  /**
   * Проверяет является ли файл картинкой
   * @param string $path
   * @return bool
   */
  public function is_image($path)
  {
      $exts = array('jpeg', 'jpg', 'png', 'gif');
      return in_array($this->get_ext($path), $exts);
  }

  /**
   * Получить расширение
   * @param string $path
   * @return string
   */
  public function get_ext($path)
  {
      return strtolower(pathinfo($path, PATHINFO_EXTENSION));//Получаем расширение файла
  }

  /**
   * Получить название файла
   * @param string $path
   * @param string $prefix
   * @return string
   */
  public function generate_name($file, $path, $prefix = '')
  {
      $name = $prefix . md5(uniqid()) .'-'. time() .'.'. self::get_ext($file);

      // Если есть файл с таким именем генерируем снова
      while (file_exists($path . $name))//Если файл с таким именем уже существует
      {
          $name = generate_name($name, $path, $prefix);//Добавляет новый уникальный uniqid и префикс
      }

      return $name;
  }
  
  public function destroy() {//Удаление записи
    // Прежде всего удаляем запись в базе данных
    if($this->delete()) {
      // Затем, удаляем файл
      // При удалении базы данных объект все еще доступен 
      // (это может быть полезным для вывода сообщения $this->image_path()).
      $target_path = SITE_ROOT.DS.$this->image_path();
      return unlink($target_path) ? true : false;//Удаляем файл
    } else {
      // удаление из базы данных не произошло
      return false;
    }
  }

  public function image_path() {
    return $this->upload_dir.DS.$this->filename;
  }
  
  public function size_as_text() {//Получаем размер файла в текстовом формате
    if($this->size < 1024) {
      return "{$this->size} bytes";
    } elseif($this->size < 1048576) {
      $size_kb = round($this->size/1024);
      return "{$size_kb} KB";
    } else {
      $size_mb = round($this->size/1048576, 1);
      return "{$size_mb} MB";
    }
  }

  public function save() {
    // Новая запись еще не будет иметь соответствующую переменную $id.
    if(isset($this->id)){
      $this->update();
    } else{
      //$this->create();
      // // Убедитесь в отсутствии ошибок
      // // Сохранение невозможно при существовании ошибок
      // if(!empty($this->errors)) { return false; }
          
      // // Сохранение невозможно при отсутствии имени файла или временного пути
      // if(empty($this->filename) || empty($this->temp_path)) {
      //   $this->errors[] = "Недоступное расположение файла.";
      //   return false;
      // }
      
      // Определяем полный путь к файлу
      $target_path = SITE_ROOT .DS. $this->upload_dir .DS. $this->filename;
      // echo($target_path);
      // echo($this->filename);
      // exit();
      // // Убеждаемся, что файл не был ранее загружен
      // // if(file_exists($target_path)) {
      // //   $this->errors[] = "Файл {$this->filename} уже существует.";
      // //   return false;
      // // }
    
      // // Подготовка к перемещению файла 
      if(move_uploaded_file($this->temp_path, $target_path) || ($this->filename = "photoPerson.png")) {
        // Если успешно
        // Сохраняем уведомление в базу данных
        if($this->create()) {//Если сохранение прошло успешно
          // Работа с temp_path завершена, файла там уже нет, и можно удалить переменную $temp_path
          unset($this->temp_path);
          return true;
        }
      } else {
        // Файл не перемещен.
        $this->errors[] = "Загрузка файла прервана. Это может быть связано с неверно установленными правами доступа к директории";
        return false;
      }
    }
  }
  
  public static function count_all() {
    global $database;
    $sql = "SELECT COUNT(*) FROM ".self::$table_name;
    $result_set = $database->query($sql);
    $row = $database->fetch_array($result_set);
    return array_shift($row);
  }
  // // Common Database Methods
  // public static function find_all() {
  //   return self::find_by_sql("SELECT * FROM ".self::$table_name);
  // }
  
  // public static function find_by_id($id=0) {
  //   $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
  //   return !empty($result_array) ? array_shift($result_array) : false;
  // }
  
  // public static function find_by_sql($sql="") {
  //   global $database;
  //   $result_set = $database->query($sql);
  //   $object_array = array();
  //   while ($row = $database->fetch_array($result_set)) {
  //     $object_array[] = self::instantiate($row);
  //   }
  //   return $object_array;
  // }

  // private static function instantiate($record) {
  //   // Проверяем существование переменной $record, и является ли она массивом
  //   $object = new self;
  //   // Простой, длинный подход:
  //   // $object->id        = $record['id'];
  //   // $object->username  = $record['username'];
  //   // $object->password  = $record['password'];
  //   // $object->first_name = $record['first_name'];
  //   // $object->last_name   = $record['last_name'];
    
  //   // Более динамический, короткий подход:
  //   foreach($record as $attribute=>$value){
  //     if($object->has_attribute($attribute)) {
  //       $object->$attribute = $value;
  //     }
  //   }
  //   return $object;
  // }
  
  // private function has_attribute($attribute) {
  //   // We don't care about the value, we just want to know if the key exists
  //   // Will return true or false
  //   return array_key_exists($attribute, $this->attributes());
  // }

  // protected function attributes() { 
  //   // return an array of attribute names and their values
  //   $attributes = array();
  //   foreach(self::$db_fields as $field) {
  //     if(property_exists($this, $field)) {
  //       $attributes[$field] = $this->$field;
  //     }
  //   }
  //   return $attributes;
  // }
  
  // protected function sanitized_attributes() {
  //   global $database;
  //   $clean_attributes = array();
  //   // sanitize the values before submitting
  //   // Note: does not alter the actual value of each attribute
  //   foreach($this->attributes() as $key => $value){
  //     $clean_attributes[$key] = $database->escape_value($value);
  //   }
  //   return $clean_attributes;
  // }
  
  // public function create() {
  //   global $database;
  //   // Don't forget your SQL syntax and good habits:
  //   // - INSERT INTO table (key, key) VALUES ('value', 'value')
  //   // - single-quotes around all values
  //   // - escape all values to prevent SQL injection
  //   $attributes = $this->sanitized_attributes();
  //   $sql = "INSERT INTO ".self::$table_name." (";
  //   $sql .= join(", ", array_keys($attributes));
  //   $sql .= ") VALUES ('";
  //   $sql .= join("', '", array_values($attributes));
  //   $sql .= "')";
  //   if($database->query($sql)) {
  //     $this->id = $database->insert_id();
  //     return true;
  //   } else {
  //     return false;
  //   }
  // }

  // public function update() {
  //   global $database;
  //   // Don't forget your SQL syntax and good habits:
  //   // - UPDATE table SET key='value', key='value' WHERE condition
  //   // - single-quotes around all values
  //   // - escape all values to prevent SQL injection
  //   $attributes = $this->sanitized_attributes();
  //   $attribute_pairs = array();
  //   foreach($attributes as $key => $value) {
  //     $attribute_pairs[] = "{$key}='{$value}'";
  //   }
  //   $sql = "UPDATE ".self::$table_name." SET ";
  //   $sql .= join(", ", $attribute_pairs);
  //   $sql .= " WHERE id=". $database->escape_value($this->id);
  //   $database->query($sql);
  //   return ($database->affected_rows() == 1) ? true : false;
  // }

  // public function delete() {
  //   global $database;
  //   // Don't forget your SQL syntax and good habits:
  //   // - DELETE FROM table WHERE condition LIMIT 1
  //   // - escape all values to prevent SQL injection
  //   // - use LIMIT 1
  //   $sql = "DELETE FROM ".self::$table_name;
  //   $sql .= " WHERE id=". $database->escape_value($this->id);
  //   $sql .= " LIMIT 1";
  //   $database->query($sql);
  //   return ($database->affected_rows() == 1) ? true : false;
  
  //   // NB: After deleting, the instance of User still 
  //   // exists, even though the database entry does not.
  //   // This can be useful, as in:
  //   //   echo $user->first_name . " was deleted";
  //   // but, for example, we can't call $user->update() 
  //   // after calling $user->delete().
  // }
}

?>