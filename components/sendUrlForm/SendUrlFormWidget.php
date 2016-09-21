<?php

namespace app\components\sendUrlForm;

use yii\base\Widget;

/**
*
* Клас инициализации виджета отправки ссылки на сервер
*
* @author Roman Tsutskov
*
*/

class SendUrlFormWidget extends Widget
{
	/**
	*
	* Свойство для хранения сообщения, поступившего из формы отправки сообщений
	*
	* @var string
	*
	*/
    public $message;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('form');
    }
}