$('.main').click(hideServiceMessage)

//Метод вывода сообщения, если не ьыло ошибки
function showMessage(messageText) {
	//Получаем указатель на текстовый блок
	textTagServiceMessage = $('#service-message');
	//Получаем указатель на блок вывода сообщений
	containerServiceMessage = $('#service-message-block');
	//Получаем занчение вывода блока сообщения
	displayValue = containerServiceMessage.css('display');
	//Добавялем сообщение в блок 
	textTagServiceMessage.text(messageText);
	//Если сообщение не выведено
	if(displayValue === 'none') {
		//Показываем его
		containerServiceMessage.show();
	}
}

//Метод вывода сообщения, если была ошибка
function showErrorMessage(messageData) {
	//Получаем указатель на текстовый блок
	textTagServiceMessage = $('#service-message');
	//Получаем указатель на блок вывода сообщений
	containerServiceMessage = $('#service-message-block');
	//Получаем занчение вывода блока сообщения
	displayValue = containerServiceMessage.css('display');
	//Изменяем фон блока с сообщением
	containerServiceMessage.css('background-color', 'rgb(216, 148, 117)');
	//Получаем тип пришедшего сообщения
	messageDataType = $.type(messageData);
	if(messageDataType === 'string') {
		textTagServiceMessage.text(messageData);
	} else {
		textTagServiceMessage.text(messageData['responseText']);
	}
	//Если сообщение не выведено
	if(displayValue === 'none') {
		containerServiceMessage.show();
	}
}

//Метод отмены вывода сообщения при клике в любом месте страницы
function hideServiceMessage() {
	containerServiceMessage = $('#service-message-block');
	displayValue = containerServiceMessage.css('display');
	if(displayValue !== 'none') {
		containerServiceMessage.css('background-color', 'rgb(119, 207, 119)');
		containerServiceMessage.hide();
	}
}