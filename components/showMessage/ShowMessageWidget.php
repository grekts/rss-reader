<?php

namespace app\components\showMessage;

use yii\base\Widget;

/**
*
* Класс инициализации виждета вывода сообщений на страницы сайта
*
* @author Roman Tsutskov
*
*/
class ShowMessageWidget extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('showMessage');
    }
}