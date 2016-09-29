<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\FeedNews;
use app\models\FeedData;

/**
*
* Класс формирования страниц сайта и вывода ошибок
*
* @author Roman Tsutskov
*
*/
class SiteController extends Controller
{
    /**
    *
    * Метод формирования главной страницы сайта
    *
    * @return string Код страницы
    * @access public
    *
    */
    public function actionIndex() {
        //Получаем список новостей, для вывода их на страницу
        $newsData = FeedNews::getNewsData();
        return $this -> render('index', ['newsData' => $newsData]);
    }

    /**
    *
    * Метод формирования страницы для выводя списка фидов
    *
    * @return string Код страницы
    * @access public
    *
    */
    public function actionFeeds() {
        //Получаем список фидов, для вывода их на страницу
        $feedData = FeedData::getFeedsData();
        return $this -> render('feeds', ['feedData' => $feedData]);
    }

    /**
    *
    * Метод формирования страницы с архивынми новостями
    *
    * @return string Код страницы
    * @access public
    *
    */
    public function actionArchive() {
        //Получаем список новостей из архива, для вывода их на страницу
        $newsData = FeedNews::getNewsDataFromArchive();
        return $this -> render('archive', ['newsData' => $newsData]);
    }

    /**
    *
    * Метод вывода сообщения об ошибке
    *
    * @return string Текст ошибки
    * @access public
    *
    */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            Yii::$app->response->content = $exception->getMessage();
        }
    }

}
