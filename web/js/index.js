$('[id ^= img-folder]').click(sendNewsToArchive);
$('[id ^= img-bucket-feed]').click(deleteFeed);
$('[id ^= img-bucket-news]').click(deleteNewsFromArchive);
$('[id ^= description]').click(showDescription);
$('[id ^= title]').click(showDescription);
$('[class ^= main-menu-button]').click(showElementSendUrl);
$('[class ^= main-menu-button]').click(showMainMenu);
$('[id ^= title]').click(setReadNews);
$('[id ^= description]').click(setReadNews);
$('[id ^= date]').click(setReadNews);
$('.folder').click(setReadNews);

//Функция отправки новости в архив
function sendNewsToArchive() {
	  newsId = this.id;
 	  splitNewsId = newsId.split('-');
    newsNumber = splitNewsId[2];
    //Получаем уникальынй идентификатор формы (т.к. иначе на сервере не пройдет проверка на валидность)
	  dataId = $('form[name="sendUrlForm"]').children('input[name="_csrf"]').val();
  	$.ajax({
    	  type: "POST",
    	  url: "news-to-archive",
      	data: {_csrf:dataId, FeedNews: {newsId:newsNumber}},
      	dataType: "text",
      	success: function(messageForUser) {
            if((messageForUser !== '') && (messageForUser !== null)) {
      			    //Выводим сообщение с помощью метода, находящегося в виджете вывода сообщений
              	showMessage(messageForUser);
                $('#img-folder-' + newsNumber).attr("src","/images/folder-active.png");
          	}
      	}
  	});
}

//Функция отправки заппроса на удаление фида
function deleteFeed() {
	  confirmResult = confirm('RSS лента будет удалена. Продолжить?');
  	if(confirmResult === true) {
  		  //Получаем id строки фида, чтобы из него получить номер удаляемого фида
		    feedId = this.id;
	 	    splitFeedId = feedId.split('-');
	 	    //Получаем id фида
	  	  feedIdText = splitFeedId[3];
	  	  //Получаем уникальынй идентификатор формы (т.к. иначе на сервере не пройдет проверка на валидность)
	  	  dataId = $('form[name="sendUrlForm"]').children('input[name="_csrf"]').val();
	  	  $.ajax({
	    	    type: "POST",
	    	    url: "delete-feed-url",
	    	    data: {_csrf:dataId, FeedId: {feedId:feedIdText}},
	    	    dataType: "text",
	    	    success: function(messageForUser) {
	    		      if((messageForUser !== '') && (messageForUser !== null)) {
	    		          //Выводим сообщение с помощью метода, находящегося в виджете вывода сообщений
             	      showMessage(messageForUser);
                    $('#row-' + feedIdText).hide();
                }
	    	    }
	  	  });
  	}
}

//Функция удаления новости из архива
function deleteNewsFromArchive()
{
	  confirmResult = confirm('Новость будет удалена. Продолжить?');
    if(confirmResult === true) {
	      newsId = this.id;
	      splitNewsId = newsId.split('-');
	      newsNumber = splitNewsId[3];
	      dataId = $('form[name="sendUrlForm"]').children('input[name="_csrf"]').val();
	      $.ajax({
 		        type: "POST",
  		      url: "delete-from-archive",
  		      data: {_csrf:dataId, FeedNews: {newsId:newsNumber}},
  		      dataType: "text",
  		      success: function(messageForUser) {
                if((messageForUser !== '') && (messageForUser !== null)) {
  			            //Выводим сообщение с помощью метода, находящегося в виджете вывода сообщений
            	      showMessage(messageForUser);
                    $('#row-' + newsNumber).hide();
        	      }
  		      }
	      });
    }
}

//Функция для отображения или закрытия описания новости
function showDescription() {
    titleId = this.id;
    splitTitleId = titleId.split('-');
    descriptionBlock = $('#description-' + splitTitleId[1]);
    if(descriptionBlock.css('display') === 'none') {
        descriptionBlock.show();
        $('#row-' + splitTitleId[1]).css('border-bottom', '0px');
        $('#row-' + splitTitleId[1] + ' p').css('color', '#a5a5a5');
    } else {
        descriptionBlock.hide();
        $('#row-' + splitTitleId[1]).css('border-bottom', '1px solid #999999');
    }
}

//Метод для отображения или скрытия подя и кнопки добавления ссылки на фид в режиме мобильной версии верстки
function showElementSendUrl() {
    mainMenu = $('ul[class ^= main-menu]');
    if(mainMenu.css('display') === 'none') {
        $('.field-write-text').show();
        $('.button-send-data').show();
    } else {
        $('.field-write-text').hide();
        $('.button-send-data').hide();
    }
}

//Метод для отображения или скрытия главномо меню в режиме мобильной версии верстки
function showMainMenu() {
    mainMenu = $('ul[class ^= main-menu]');
    if(mainMenu.css('display') === 'none') {
        mainMenu.show();
        $('.header-height').css('height', '160px');
    } else {
        mainMenu.hide();
        $('.header-height').css('height', '80px');
    }
}

//Метод отправки на сервер данных для установки в базе флага, говорящего что нвость прочитана
function setReadNews() {
    titleId = this.id;
    splitTitleId = titleId.split('-');
    //Считаем части строки id, т.к. несколько элементов, использующие функцию, имеют разную длиину id
    numberidPart = splitTitleId.length;
    if(numberidPart == 2) {
        numberFromTitleId = splitTitleId[1];
    } else {
        numberFromTitleId = splitTitleId[2];
    }
    dataId = $('form[name="sendUrlForm"]').children('input[name="_csrf"]').val();
    $.ajax({
        type: "POST",
        url: "set-read",
        data: {_csrf:dataId, FeedNews: {newsId:numberFromTitleId}},
        dataType: "text",
        success: function(messageForUser) {
            if((messageForUser !== '') && (messageForUser !== null)) {
                //Выводим сообщение с помощью метода, находящегося в виджете вывода сообщений
                showMessage(messageForUser);
            }
        }
    });
}