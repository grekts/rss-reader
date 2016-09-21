<?php
use yii\helpers\Html;
?>
<?= Html::beginForm(
	'send-url', 
	'post', 
	[
		'name' => "sendUrlForm",
		'accept-charset' => 'utf-8',
		'enctype' => 'multipart/form-data',
	]
) ?>
	<?= Html::input('text', 
		'sendUrlFormInput', 
		'Ссылка', 
		[
			'class' => 'field-write-text',
		]
	) ?>
<?= Html::endForm() ?>
<?= Html::submitButton(
		'Отправить', 
		[
			'class' => 'button-send-data cursor-pointer',
			'onClick' => 'sendUrlFormAction()',
			'name' => 'sendUrlFormButton',
		]
	) ?>