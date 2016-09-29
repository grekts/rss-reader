<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use app\models\FeedUrl;
use app\models\FeedId;
use app\models\FeedData;
use yii\base\UserException;
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
		//Если от клиента пришли данные
		if($feedUrlData !== []) {
			//Подклчюаем модель формы отправки ссылки на фид
			$model = new FeedUrl();
			if ($model->load($feedUrlData) === true) {
				//Проверяем входящие данные
				if($model->validate() === true) {
					//Создаем экземпляр формировщика полной ссылки
					$getterFullLink = new GetterFullLink();
					//Получаем полную проверенную на работоспособность ссылку
					$fullUrl = $getterFullLink -> getLink($model->url);
					//отправляем его в базу
			        $indertResult = FeedData::sendFeedData($fullUrl);
				} else {
					throw new UserException(Html::encode($model->errors['url'][0]));
				}
	        } else {
	            throw new UserException("Не указаны требуемые данные");
	        }
	    } else {
	    	throw new UserException("Не указаны требуемые данные");
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
		//Загружаем модель идентификатора фида
		$model = new FeedId();
		//Получаем данные фида от пользваотеля
		$feedIdData = Yii::$app->request->post();
		//Если от пользователя пришли какие-то данные
		if($feedIdData !== []) {
			//Если мы успешно загрузили их в модель
			if($model->load($feedIdData)) {
				//Если валидация полученных данных прошла успешно
				if($model->validate()) {
					FeedData::deleteFeedData($model->feedId);
				} else {
					throw new UserException(Html::encode($model->errors['feedId'][0]));
				}
			} else {
				throw new UserException("Не указаны требуемые данные");
			}
		} else {
			throw new UserException("Не указаны требуемые данные");
		}
	}
}