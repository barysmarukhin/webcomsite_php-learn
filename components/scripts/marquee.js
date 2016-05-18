/* JavaScript Document */

var marqueeVars = {
	screenSize: '',
	width:100,
	mobileSize: 400,
	autoPlay: true,
	currentPanel: 1,
	totalPanels: 0,
	timePassed: 0,
	timeToChange: 60,
	duration: 1250,
	inTransition: false,
	panelContent: Array
};

$(document).ready(function(){
	marqueeGatherData();
});

function marqueeGatherData(){
	$('.marquee_data .marquee_panel').each(function(index){//контекст на каждом отдельном блоке с контентом, в функцию передается индекс массива с элементами
		marqueeVars.totalPanels = index + 1;//в итоге marqueeVars.totalPanels=3, так как в html коде существует 3 элемента с классом .marquee_panel
		var panel_image_l = $(this).attr('data-image')+'_l.jpg';//Путь к большой картинке
		var panel_image_s = $(this).attr('data-image')+'_s.jpg';//Путь к маленькой картинкед
		var panel_caption = $(this).html();//получаем разментку внутри отдельного блока с контентом
		marqueeVars.panelContent[index] = '<div class="marquee_panel" data-image-s="'+panel_image_s+'" style="background-image:url('+panel_image_l+');"><div class="overlay"><div class="panel_caption">'+panel_caption+'</div></div></div>';//Формируем контент панели, маленькая картинка в дата атрибуте, большая на странице, добавляем разметку с описанием panel-caption
	});
	var marqueeTimer = setInterval(marqueeAdvance, 100);//Запускаем функцию marqueeAdvance 10 раз в секунду

}

function marqueeAdvance(){
	var marqueeWidth = $('.marquee').width();//Ширина всего блока галереи
	var currentSize = marqueeVars.screenSize;//Размер экрана(пустая строка изначально)

	if( marqueeWidth > marqueeVars.mobileSize ){ //Сравниваем с мобильным экраном(600px)
		var newSize = 'large';
	} else{
		var newSize = 'small';
	}
	marqueeVars.screenSize = newSize;//свойство screenSize принимает large или small по результатам сравнения

	if(currentSize != newSize){//
		if( marqueeVars.screenSize=='large'){
			marqueeMultiPanel();// Если новый размер экрана large, то вызываем функцию marqueeMultiPanel
		}else{
			marqueeSinglePanel();//Вызываем функцию, формирующую галерею из одной лишь картинки
		}
	}

	if( marqueeVars.timePassed == marqueeVars.timeToChange){//Если достигнуто время смены
		marqueeVars.timePassed = 0;

		if (marqueeVars.autoPlay == true) {
			if (marqueeVars.currentPanel == marqueeVars.totalPanels) { //Если текущая панель последняя
				$('.marquee_nav div:nth-child(1)').trigger('click');//Иммитируем клик по первому элементу
			}else{
				$('.marquee_nav div:nth-child('+(marqueeVars.currentPanel+1)+')').trigger('click');//Иммитируем клик по следующему элементу
			}
		}

	}else{
		marqueeVars.timePassed +=1;//Увеличиваем счетчик, если время смены не достигнуто
	}
}

function marqueeMultiPanel(){
	marqueeVars.timePassed = 0;
	marqueeVars.autoPlay = true;
	var newHTML = '<div class="marquee_stage_large"><div class="marquee_container_1"></div><div class="marquee_nav"></div><div class="btn prev"></div><div class="btn next"></div></div>';
	$('.marquee').html('').append(newHTML);//Вставляем новую разметку(контейнер с картинкой, нижняя навигация и стрелки), удаляя старую

	for( i = 0; i < marqueeVars.totalPanels; i++){ // есть 3 элемента в index.html c классом marquee_panel, поэтому значение marqueeVars.totalPanels = 3
		$('.marquee_nav').append('<div></div>')//Добавляем элементы навигации в зависимости от количества контента
	}

	$('.marquee').hover(function(){
		marqueeVars.autoPlay = false;
	}, function(){
		marqueeVars.autoPlay = true;
		marqueeVars.timePassed = Math.floor(marqueeVars.timeToChange/2);// Время отсчета при уходе фокуса делаем вдвое меньше времени смены
	});

	$('.marquee .btn').on('click', function(){//Обрабанываем клик на правую или левую стрелку
		if(!marqueeVars.inTransition){//Запрещаем клики в моменты смены картинки

			if ($(this).hasClass('prev')) {
				marqueeVars.currentPanel -= 1;//Берем заданное в объекта свойство currentPanel и вычитаем единицу
				if (marqueeVars.currentPanel < 1) {
					marqueeVars.currentPanel = marqueeVars.totalPanels;//Если текущая панель первая, перескакиваем на последнюю
				}
			}else{
				marqueeVars.currentPanel += 1;
				if(marqueeVars.currentPanel > marqueeVars.totalPanels){
					marqueeVars.currentPanel = 1;
				}
			}

			$('.marquee_nav div:nth-child('+marqueeVars.currentPanel+')').trigger('click');//Имитируем событие click для нижних кнопок навигации при клике на стрелку чтобы поменять картинку
		}
	});


	$('.marquee_nav div').on('click', function(){//Это нижние кнопки навигации, вешаем событие на их клик
		
		if(!marqueeVars.inTransition){ //Запрещаем клики в моменты смены картинки

			marqueeVars.inTransition = true;

			var navClicked = $(this).index();//Получаем индекс(0,1,2) кликнутого дива внутри элемента с классом marquee_nav
			marqueeVars.currentPanel = navClicked +1;//Номер текущей панели, начиная с 1

			$('.marquee_nav div').removeClass('selected');//Удаляем selected со всех div внутри marqee_nav
			$(this).addClass('selected');//Добавляем selected в текущий кликнутый(или через выше описанный trigger) div

			$('.marquee_stage_large').append('<div class="marquee_container_2" style="opacity:0;"></div>');//Добавляем невидимый новый контейнер
			$('.marquee_container_2').html(marqueeVars.panelContent[navClicked]).animate({opacity:1}, marqueeVars.duration,function(){
				$('.marquee_container_1').remove();//Удаляем существовавший div с классом .marquee_container_1
				$(this).addClass('marquee_container_1').removeClass('marquee_container_2');//Довавляем класс marquee_container_1 к тегу с классом marquee_container_2 и удаляем класс marquee_container_2
				marqueeVars.inTransition = false;
			});//Добавляем невидимый контент из ранее созданного массива к текущему элементу, и делаем его видимым, переприсваиваем классы
		}		
	});

	$('.marquee_nav div:first').trigger('click');//Имируем клик для первого элемента, чтобы отобразить его при загрузке страницы
}

function marqueeSinglePanel(){
	$('.marquee').html('').append('<div class="marquee_stage_small">'+marqueeVars.panelContent[0]+'</div>');//Удаляем разметку из html файла и добавляем ранее созданный контент из первого элемента массива на страницу
	var panel_image_s = $('.marquee .marquee_stage_small .marquee_panel').attr('data-image-s');//Берем путь к маленькой картинке из созданного ранее дата атрибута
	$('.marquee .marquee_stage_small .marquee_panel').css('background-image','url('+panel_image_s+')');//Вставляем маленькую картинку в .marquee_panel
}


/*//debugger
var debugTimer = setInterval(setDebugger, 100);
function setDebugger(){
	$('.var1').html('screenSize = '+ marqueeVars.screenSize);
	$('.var2').html('width = '+ marqueeVars.width);
	$('.var3').html('mobileSize = '+ marqueeVars.mobileSize);
	$('.var4').html('autoPlay = '+ marqueeVars.autoPlay);
	$('.var5').html('currentPanel = '+ marqueeVars.currentPanel);
	$('.var6').html('totalPanels = '+ marqueeVars.totalPanels);
	$('.var7').html('timePassed = '+ marqueeVars.timePassed);
	$('.var8').html('timeToChange = '+ marqueeVars.timeToChange);
	$('.var9').html('inTransition = '+ marqueeVars.inTransition);
}*/
