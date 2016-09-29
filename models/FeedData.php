<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;
use yii\base\UserException;
use yii\db\Exception;

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
        try {
            Yii::$app->db->createCommand('INSERT INTO feeds VALUES (:id, :url)')
                ->bindValues([':id' => null, ':url' => $fullUrl])
                ->execute();
            Yii::$app->response->content = 'Ссылка на фид успешно сохранена';
        } catch (Exception $e) {
            throw new UserException("Указанный фид был сохранен ранее");
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
        try {
            $deleteResult = Yii::$app->db->createCommand('DELETE FROM feeds WHERE feed_id = :id')
                ->bindValues([':id' => $feedId])
                ->execute();
        } catch (Exception $e) {
            throw new ErrorException("Ошибка системы");
        }

        //Если при удалении был удален хотя бы один элемент
        if($deleteResult !== 0) {
            Yii::$app->response->content = 'Фид удален из базы';
        } else {
            throw new UserException("Указанного фида нет в системе");
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
        } catch (Exeption $e) {
             throw new ErrorException("Ошибка системы");
        }
    }
}