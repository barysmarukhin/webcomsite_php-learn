(function (){ 
      var callback_link = document.querySelectorAll("#callback");
      var popup = document.querySelector(".modal-content");
      var close = document.querySelector(".modal-content-close");
      var form = popup.querySelector(".login-form");
      var login = popup.querySelector("[name=login]");
      var password = popup.querySelector("[name=number]");
      var storage = localStorage.getItem("login");


       for(var i = callback_link.length - 1; i >= 0; i--){
          callback_link[i].addEventListener("click", function(event) {
            event.preventDefault();
            popup.classList.add("modal-content-show");
            if (storage) {
              login.value = storage;
              password.focus();
            } else {
              login.focus();
            }
          });
        };
      
      close.addEventListener("click", function(event) {
        event.preventDefault();
        popup.classList.remove("modal-content-show");
      });

      form.addEventListener("submit", function(event) {
        if (!(login.value && password.value)) {
          event.preventDefault();
          popup.classList.remove("modal-error");
          popup.classList.add("modal-error");
        } else {
          localStorage.setItem("login", login.value);
        }
      });

      window.addEventListener("keydown", function(event) {
        if (event.keyCode == 27) {
          if (popup.classList.contains("modal-content-show")) {
            popup.classList.remove("modal-content-show");
          }
        }
      });

})();//self executing function