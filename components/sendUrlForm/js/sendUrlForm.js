$('.field-write-text').click(setFocusOnInput);
$('.field-write-text').blur(blurOutofInput);

function sendUrlFormAction() {
    //Получаем указатель на поле ввода
	formSendUrl = $('form[name="sendUrlForm"]');
    //Получаем уникальный идентификатор формы
	formId = formSendUrl.children('input[name="_csrf"]').val();
    //Получаем указанные в поле ввода данные
	urlForSend = formSendUrl.children('input[name="sendUrlFormInput"]').val();
    //Если в поле ффода не указано значение по-умолчанию и оно не пустое
    if((urlForSend !== 'Ссылка') && (urlForSend !== '')) {
        //Формируем регулярное выражение, описывающее ссылку
        regex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
        //Проверяем введен ли в поле адрес электорнной почты
        if(regex.test(urlForSend) === true) {
            //Отправляем на сервер ссылку и идентификатор
            $.ajax({
            type: "POST",
                url: "send-feed-url",
                data: {_csrf:formId, FeedUrl: {url:urlForSend}},
                dataType: "text",
                success: function(messageForUser) {
                    if((messageForUser !== '') && (messageForUser !== null)) {
                        showMessage(messageForUser);
                    }
                },
                error: function(messageForUser) {
                    if((messageForUser !== '') && (messageForUser !== null)) {
                        showErrorMessage(messageForUser);
                    }
                },
            });
        } else {
            showErrorMessage('Указанные данные не являются ссылкой');
        }
    } else {
        showErrorMessage('Ссылка не указана');
    }
}

function setFocusOnInput() {
    $inputField = $('.field-write-text');
    if($inputField.val() === 'Ссылка') {
        $inputField.val('');
        $inputField.css("color", '#000000')
    }
}

function blurOutofInput() {
    $inputField = $('.field-write-text');
    if($inputField.val() === '') {
        $inputField.val('Ссылка');
        $inputField.css("color", '#bcbcbc')
    }
}