<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\FeedNews;
use app\models\FeedData;

class SiteController extends Controller
{

    public function actionIndex() {
        $newsData = FeedNews::getNewsData();
        return $this -> render('index', ['newsData' => $newsData]);
    }

    public function actionFeeds() {
        $feedData = FeedData::getFeedsData();
        return $this -> render('feeds', ['feedData' => $feedData]);
    }

    public function actionArchive() {
        $newsData = FeedNews::getNewsDataFromArchive();
        return $this -> render('archive', ['newsData' => $newsData]);
    }


    // /**
    //  * @inheritdoc
    //  */
    // public function actions()
    // {
    //     return [
    //         'error' => [
    //             'class' => 'yii\web\ErrorAction',
    //         ],
    //     ];
    // }

    // public function actionError()
    // {
    //     $exception = Yii::$app->errorHandler->exception;
    //     if ($exception !== null) {
    //         return $this->render('error', ['exception' => $exception]);
    //     }
    // }

}
