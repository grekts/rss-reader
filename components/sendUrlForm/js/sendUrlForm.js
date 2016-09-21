$('.field-write-text').click(setFocusOnInput);
$('.field-write-text').blur(blurOutofInput);

function sendUrlFormAction() {
	formSendUrl = $('form[name="sendUrlForm"]');
	formId = formSendUrl.children('input[name="_csrf"]').val();
	urlForSend = formSendUrl.children('input[name="sendUrlFormInput"]').val();
    if(urlForSend === 'Ссылка') {
        urlForSend = '';
    }
	$.ajax({
      type: "POST",
        url: "send-feed-url",
        data: {_csrf:formId, FeedUrl: {url:urlForSend}},
        dataType: "text",
        success: function(messageForUser) {
            if((messageForUser !== '') && (messageForUser !== null)) {
                showMessage(messageForUser);
            }
        }
    });
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