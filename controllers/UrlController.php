<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use app\models\FeedUrl;
use app\models\FeedId;
use app\models\FeedData;
use getterfulllink\GetterFullLink;

/**
*
* Контроллер отвечает за обработку ссылок на фиды
*
* @author Roman Tsutskov
*/
class UrlController extends Controller
{
	/**
	*
	* Метод отправки ссылки на фид в БД
	*
	* @return string Сообщение о результате отправки ссылки в БД
	* @access public
	*/
	public function actionSendUrl() {
		//Получаем массив с данными из формы отправки ссылки на фид
		$feedUrlData = Yii::$app->request->post();
		//Подклчюаем модель формы отправки ссылки на фид
		$model = new FeedUrl();
		if ($model->load($feedUrlData) && $model->validate()) {
			//Создаем экземпляр формировщика полной ссылки
			$getterFullLink = new GetterFullLink();
			//Получаем полную проверенную на работоспособность ссылку
			$fullUrl = $getterFullLink -> getLink($model->url);
			if($fullUrl !== false) {
				//Получаем список id фидов, находящихся в базе данных
				$feedId = FeedId::getFeedsId($fullUrl);
		        //Если фид в базу раньше не добавлялся
		        if($feedId === false) {
		        	//отправляем его в базу
		        	$indertResult = FeedData::sendFeedData($fullUrl);
		        	if($indertResult !== true) {
		        		Yii::$app->response->content = $indertResult;
		        	} else {
		            	Yii::$app->response->content = 'Ссылка на фид успешно сохранена';
		            }
		        } else { //Если фид уже был записан в базу ранее
		        	//Отправялем сообщение пользователю
		        	Yii::$app->response->content = 'Указанный фид был сохранен ранее';
		        }
		    } else {
		    	Yii::$app->response->content = 'Указанной ссылки не существует';
		    }
        } else {
            Yii::$app->response->content = Html::encode($model->errors['url'][0]);
        }
	}

	/**
	*
	* Метод удвления из базы данных ссылки на фид
	*
	* @return string Сообщение о результате удаления ссылки из БД
	* @access public
	*/	
	public function actionDeleteFeedUrl() {
		$model = new FeedId();
		$feedIdData = Yii::$app->request->post();
		if($model->load($feedIdData) && $model->validate()) {
			FeedData::deleteFeedData($model->feedId);
		} else {
			Yii::$app->response->content = Html::encode($model->errors['feedId'][0]);
		}
	}
}