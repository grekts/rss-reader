<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;

/**
* Модель работы одновременно со всеми данными фида
*
* @author Roman Tsutskov
*/
class FeedData extends Model
{	

    /**
    *
    * Метод отправки данных вида в базу данных
    *
    * @param string $fullUrl Ссылка фида, подлежащая отправки в базу данных
    * @access public
    * @static
    *
    */
    public static function sendFeedData($fullUrl)
    {
        $typeData = gettype($fullUrl);
        if(($typeData === 'string') && ($fullUrl !== '')) {
            try {
                Yii::$app->db->createCommand('INSERT INTO feeds VALUES (:id, :url)')
                    ->bindValues([':id' => null, ':url' => $fullUrl])
                    ->execute();
                return true;
            } catch (ErrorException $e) {
                throw new ErrorException("Ошибка при отправке данных фида в базу");
            }
        } else {
            throw new ErrorException("Тип входных данных не соответствует string или не переданы");
        }
    }

    /**
    *
    * Метод удаления данных вида из базу данных
    *
    * @param string $feedId Id фида, который необходимос удалить из базы данных
    * @access public
    * @static
    *
    */
    public static function deleteFeedData($feedId)
    {
        $typeData = gettype($feedId);
        if(($typeData === 'string') && ($feedId !== '')) {
            try {
                $deleteResult = Yii::$app->db->createCommand('DELETE FROM feeds WHERE feed_id = :id')
                    ->bindValues([':id' => $feedId])
                    ->execute();
                if($deleteResult !== false) {
                    Yii::$app->response->content = 'Фид удален из базы';
                } else {
                    Yii::$app->response->content = 'Произошла ошибка при удалении фида';
                }
            } catch (ErrorException $e) {
                throw new ErrorException("Ошибка при отправке данных фида в базу");
            }
        } else {
            throw new ErrorException("Тип входных данных не соответствует string или не переданы");
        }
    }

    /**
    *
    * Метод получения списка фидов для вывода их на странице сайта
    *
    * @access public
    * @static
    *
    */
    public static function getFeedsData() {
        try {
            return Yii::$app->db->createCommand('SELECT feed_id, feed_url FROM feeds')->queryAll();
        } catch (ErrorExeption $e) {
            throw new ErrorException("Ошибка при получении из базы данных списка фидов");
        }
    }
}