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
class FeedUrl extends Model
{	
    /**
     * Ссылка на фид
     * 
     * @var string
    */
    public $url;

    public function rules()
    {
        return [
            ['url', 'required', 'message' => 'Ссылка на фид не указана'],
            ['url', 'url', 'defaultScheme' => 'http', 'message' => 'Указанные данные не являются ссылкой'],
        ];
    }

    /**
    *
    * Метод получения из базы данных списка ссылок на фиды, которые будут парсится
    *
    * @access public
    * @static
    */
    public static function getFeedsLinkFromDb() {
        try {
            return Yii::$app->db->createCommand('SELECT feed_url, feed_id FROM feeds')->queryAll();
        } catch (ErrorExeption $e) {
            throw new ErrorException("Ошибка при получении списка фидов из базы данных");
        }
    }
}