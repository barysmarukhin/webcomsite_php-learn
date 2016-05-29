<?php

// This is a helper class to make paginating 
// records easy.
class Pagination {
  
  public $current_page;
  public $per_page;
  public $total_count;

  public function __construct($page=1, $per_page=20, $total_count=0){
    $this->current_page = (int)$page;
    $this->per_page = (int)$per_page;
    $this->total_count = (int)$total_count;
  }

  public function offset() {
    // Допускаем 20 элементов на странице по умолчанию:
    // страница 1 имеет смещение 0    (1-1) * 20
    // страница 2 имеет смещение 20   (2-1) * 20
    // другими словами, страница 2 начинается с 21-го элемента
    return ($this->current_page - 1) * $this->per_page;
  }

  public function total_pages() {//Получаем количество страниц
    return ceil($this->total_count/$this->per_page);
  }
  
  public function previous_page() {//Предыдущая страница
    return $this->current_page - 1;
  }
  
  public function next_page() {//Следующая страница
    return $this->current_page + 1;
  }

  public function has_previous_page() {
    return $this->previous_page() >= 1 ? true : false;
  }

  public function has_next_page() {
    return $this->next_page() <= $this->total_pages() ? true : false;
  }
}

?>