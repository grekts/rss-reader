<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;

/**
* Модель ссылок фидов
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
            ['feedId', 'required', 'message' => 'Не указан идентификатор фида'],
            ['feedId', 'integer', 'message' => 'Тип идентификатора фида не соответсвует требуемому'],
        ];
    }

    /**
    *
    * Метод получения из базы данных списка id фидов
    *
    * @param string $fullUrl Сыылка на фид, id которого необходимо получить
    * @return array $feedId Возвращает массив с id запрашиваемого фида
    * @access public
    * @static
    *
    */
    public static function getFeedsId($fullUrl) {
        $dataType = gettype($fullUrl);
        if(($dataType === 'string') && ($fullUrl !== '')) {
            try {
                //Делаем запрос к базе данных для проверки наличия в б ней ссылки на полученный из формы фид
                $feedId = Yii::$app->db->createCommand('SELECT feed_id FROM feeds WHERE feed_url = :url')
                    ->bindValue('url', $fullUrl)
                    ->queryOne();

                return $feedId;
            } catch (ErrorException $e) {
                throw new ErrorException("Ошибка при получении списка id фидов из базы данных");
            }
        } else {
            if($dataType !== 'string') {
                throw new ErrorException("Тип данных входного параметра не соответствует типу string");
            }
            if($fullUrl === '') {
                throw new ErrorException("Не указаны данные во входном параметре");
            }
        }
    }
}