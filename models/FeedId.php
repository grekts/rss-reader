<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
* Модель идекнтификатора фидов
*
* @author Roman Tsutskov
*/
class FeedId extends Model
{	
	/**
	 * Id фида в базе данных
	 * 
	 * @var string
	*/
    public $feedId;

    /**
    *
    * Метод опредеяет правило валидации id фида
    *
    * @access public
    */
    public function rules()
    {
        return [
            ['feedId', 'trim'],
            ['feedId', 'required', 'message' => 'Не указан идентификатор фида'],
            ['feedId', 'integer', 'message' => 'Тип идентификатора фида не соответсвует требуемому'],
        ];
    }
}