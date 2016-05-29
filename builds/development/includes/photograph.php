<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Photograph extends DatabaseObject {
  
  protected static $table_name="photographs";
  protected static $db_fields=array('id', 'filename', 'type', 'size');
  public $id;
  public $filename;
  public $type;
  public $size;
  
  private $temp_path;
  protected $upload_dir="images/reviews";
  public $errors=array();
  
  protected $upload_errors = array(
    // http://www.php.net/manual/en/features.file-upload.errors.php
    UPLOAD_ERR_OK         => "Нет ошибок.",
    UPLOAD_ERR_INI_SIZE   => "Размер файла больше, чем upload_max_filesize.",
    UPLOAD_ERR_FORM_SIZE  => "Размер формы превышает MAX_FILE_SIZE.",
    UPLOAD_ERR_PARTIAL    => "Файл не был загружен полностью.",
    UPLOAD_ERR_NO_FILE    => "Нет файла.",
    UPLOAD_ERR_NO_TMP_DIR => "Нет временной директории.",
    UPLOAD_ERR_CANT_WRITE => "Невозможно записать на диск.",
    UPLOAD_ERR_EXTENSION  => "Загрузка файла приостановлена из-за ошибок расширения."
  );

  // Передаем файл в глобальный массив $_FILE(['uploaded_file']) как аргумент
  public function attach_file($file) {
    // Выполняем проверку ошибок для формы при отсутствии выбранного файла
    if(!$file || empty($file) || !is_array($file)) {
      // если ничего не загружено, загружаем пустую картинку
      //$this->errors[] = "No file was uploaded.";
      $this->filename = "photoPerson.png"
      return false;
    } elseif($file['error'] != 0) {
      // ошибка: что-то не так со стороны PHP
      $this->errors[] = $this->upload_errors[$file['error']];
      return false;
    } else {
      // Устанавливаем атрибуты объекта для данных из формы
      $this->temp_path  = $file['tmp_name'];//Временный путь к файлу
      $this->filename   = basename($file['name']);//Имя файла без пути к нему
      $this->type       = $file['type'];//Тип mime 
      $this->size       = $file['size'];//Размер файла
      // Еще не стоит беспокоиться о сохранении чего-либо в базу данных.
      return true;

    }
  }
  
  public function save() {    
    // Убедитесь в отсутствии ошибок
    // Сохранение невозможно при существовании ошибок
    if(!empty($this->errors)) { return false; }
    
    // Убедитесь, что подпись не слишком длинная для базы данных
    if(strlen($this->caption) > 255) {
      $this->errors[] = "Подпись не должна превышать 255 символов.";
      return false;
    }
  
    // Сохранение невозможно при отсутствии имени файла или временного пути
    if(empty($this->filename) || empty($this->temp_path)) {
      $this->errors[] = "Недоступное расположение файла.";
      return false;
    }
    
    // Определяем полный путь к файлу
    $target_path = SITE_ROOT .DS. $this->upload_dir .DS. $this->filename;
    
    // Убеждаемся, что файл не был ранее загружен
    if(file_exists($target_path)) {
      $this->errors[] = "Файл {$this->filename} уже существует.";
      return false;
    }
  
    // Подготовка к перемещению файла 
    if(move_uploaded_file($this->temp_path, $target_path)) {
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
  
  public function destroy() {
    // Прежде всего удаляем запись в базе данных
    if($this->delete()) {
      // Затем, удаляем файл
      // При удалении базы данных объект все еще доступен 
      // (это может быть полезным $this->image_path()).
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
  
  public function comments() {//Получаем комментарии с id фотографии
    return Comment::find_comments_on($this->id);
  }
  
  // Common Database Methods
  public static function find_all() {
    return self::find_by_sql("SELECT * FROM ".self::$table_name);
  }
  
  public static function find_by_id($id=0) {
    global $database;
    $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id=".$database->escape_value($id)." LIMIT 1");
    return !empty($result_array) ? array_shift($result_array) : false;
  }
  
  public static function find_by_sql($sql="") {
    global $database;
    $result_set = $database->query($sql);
    $object_array = array();
    while ($row = $database->fetch_array($result_set)) {
      $object_array[] = self::instantiate($row);
    }
    return $object_array;
  }

  public static function count_all() {
    global $database;
    $sql = "SELECT COUNT(*) FROM ".self::$table_name;
    $result_set = $database->query($sql);
    $row = $database->fetch_array($result_set);
    return array_shift($row);
  }

  private static function instantiate($record) {
    // Could check that $record exists and is an array
    $object = new self;
    // Simple, long-form approach:
    // $object->id        = $record['id'];
    // $object->username  = $record['username'];
    // $object->password  = $record['password'];
    // $object->first_name = $record['first_name'];
    // $object->last_name   = $record['last_name'];
    
    // More dynamic, short-form approach:
    foreach($record as $attribute=>$value){
      if($object->has_attribute($attribute)) {
        $object->$attribute = $value;
      }
    }
    return $object;
  }
  
  private function has_attribute($attribute) {
    // We don't care about the value, we just want to know if the key exists
    // Will return true or false
    return array_key_exists($attribute, $this->attributes());
  }

  protected function attributes() { 
    // return an array of attribute names and their values
    $attributes = array();
    foreach(self::$db_fields as $field) {
      if(property_exists($this, $field)) {
        $attributes[$field] = $this->$field;
      }
    }
    return $attributes;
  }
  
  protected function sanitized_attributes() {
    global $database;
    $clean_attributes = array();
    // sanitize the values before submitting
    // Note: does not alter the actual value of each attribute
    foreach($this->attributes() as $key => $value){
      $clean_attributes[$key] = $database->escape_value($value);
    }
    return $clean_attributes;
  }
  
  // replaced with a custom save()
  // public function save() {
  //   // A new record won't have an id yet.
  //   return isset($this->id) ? $this->update() : $this->create();
  // }
  
  public function create() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - INSERT INTO table (key, key) VALUES ('value', 'value')
    // - single-quotes around all values
    // - escape all values to prevent SQL injection
    $attributes = $this->sanitized_attributes();
    $sql = "INSERT INTO ".self::$table_name." (";
    $sql .= join(", ", array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));
    $sql .= "')";
    if($database->query($sql)) {
      $this->id = $database->insert_id();
      return true;
    } else {
      return false;
    }
  }

  public function update() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - UPDATE table SET key='value', key='value' WHERE condition
    // - single-quotes around all values
    // - escape all values to prevent SQL injection
    $attributes = $this->sanitized_attributes();
    $attribute_pairs = array();
    foreach($attributes as $key => $value) {
      $attribute_pairs[] = "{$key}='{$value}'";
    }
    $sql = "UPDATE ".self::$table_name." SET ";
    $sql .= join(", ", $attribute_pairs);
    $sql .= " WHERE id=". $database->escape_value($this->id);
    $database->query($sql);
    return ($database->affected_rows() == 1) ? true : false;
  }

  public function delete() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - DELETE FROM table WHERE condition LIMIT 1
    // - escape all values to prevent SQL injection
    // - use LIMIT 1
    $sql = "DELETE FROM ".self::$table_name;
    $sql .= " WHERE id=". $database->escape_value($this->id);
    $sql .= " LIMIT 1";
    $database->query($sql);
    return ($database->affected_rows() == 1) ? true : false;
  
    // NB: After deleting, the instance of User still 
    // exists, even though the database entry does not.
    // This can be useful, as in:
    //   echo $user->first_name . " was deleted";
    // but, for example, we can't call $user->update() 
    // after calling $user->delete().
  }

}

?>