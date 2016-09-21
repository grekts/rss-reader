$('html').click(hideServiceMessage)

function showMessage(messageText) {
	textTagServiceMessage = $('#service-message');
	containerServiceMessage = $('#service-message-block');
	displayValue = containerServiceMessage.css('display');
	if(displayValue === 'none') {
		containerServiceMessage.show();
	}
	$('#service-message').text(messageText);
}

function hideServiceMessage() {
	containerServiceMessage = $('#service-message-block');
	displayValue = containerServiceMessage.css('display');
	if(displayValue !== 'none') {
		containerServiceMessage.hide();
	}
}