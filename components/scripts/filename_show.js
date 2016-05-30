var myModule = (function(){//Модуль показывает на экране имя загружаемого файла

//Инициализирует наш модуль
  var init = function(){
    _setUpListeners();
    //то, что должно произойти сразу
    };

//Прослушивает события
  var _setUpListeners=function(){
    $('#fileupload').on('change', _changefileUpload); //добавление файла
    };

//Изменяет файл аплоад
  var _changefileUpload = function() {
    var input = $(this), //input type="file"
        filename = input.val(); //имя загруженного элемент
        filename = getNameFromPath(filename); //Передаем функции значение input

        // Получаем название файла из пути
          function getNameFromPath () {
              return filename.replace(/\\/g, '/').replace(/.*\//, ''); //Получаем название файла из пути
          }

    $('#filename').val(filename);

  };

//Возвращаем объект(публичные методы)
  return {
    init:init,
  };
})();
myModule.init();