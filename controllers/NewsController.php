<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use app\models\FeedUrl;
use app\models\FeedNews;
use rssparser\RssParser;

/**
*
* Контроллер отвечает за работу с кодом rss лент
*
* @author Roman Tsutskov
*/
class NewsController extends Controller
{
	/**
	*
	* Метод получения новостей по ссылкам фидов и сохранения их в базе данных
	*
	* @access public
	*/
	public function actionSaveNews() {
		//Получение списка ссылок на фиды, сохраненные в базе
		$feedsData = FeedUrl::getFeedsLinkFromDb();
		//Получаем ссылки новостей, уже хранящихся в базе
		$newsUrls = FeedNews::getNewsUrl();
		//Проходим каждую ссылку фида
		foreach ($feedsData as $feedData) {
			//Создаем объект парсера
			$rssParser = new RssParser();
			//Парсим фид по ссылке и получаем массив с контентом новостей
			$newsData = $rssParser -> parse($feedData['feed_url']);
			//Проходим каждую новость
			foreach ($newsData as $dataOneNews) {
				//Флаг определяет есть ли в базе данных новость, расположенная по определенной ссылке
				$flagNewsInDb = false;
				//Проходим все ссылки на новости, уже находящиеся в базе
				foreach ($newsUrls  as $newsUrl) {
					//Если ссылка на текущую новую нвоость совпадает с ссылкой одной из новостей, находящихся в базе
					if($newsUrl['news_link'] === $dataOneNews['link']) {
						//Устанавливаем флаг что новость уже есть в базе
						$flagNewsInDb = true;
						break;
					}
				}
				//Если новости нет в базе
				if($flagNewsInDb === false) {
					//Сохраняем новость в базе данных
					FeedNews::saveNews($dataOneNews, $feedData['feed_id']);
				}
			}
		}
	}

	/**
	*
	* Действие по переносу новостей в архив
	*
	* @access public
	*/
	public function actionNewsToArchive() {
		$model = new FeedNews();
		$newsIdData = Yii::$app->request->post();
		if($model->load($newsIdData) && $model->validate()) {
			FeedNews::sendNewsDataToArchive($model->newsId);
		} else {
			Yii::$app->response->content = Html::encode($model->errors['newsId'][0]);
		}
	}

	/**
	*
	* Действие по удалению новости из архива
	*
	* @access public
	*/
	public function actionDeleteArchiveNews() {
		$model = new FeedNews();
		$newsIdData = Yii::$app->request->post();
		if($model->load($newsIdData) && $model->validate()) {
			FeedNews::deleteNewsFromArchive($model->newsId);
		} else {
			Yii::$app->response->content = Html::encode($model->errors['newsId'][0]);
		}
	}

	/**
	*
	* Метод установления флага, обозначающего что новость была прочитана и ее не нужно больше выводить польователю
	*
	* @access public
	*/
	public function actionSetRead() {
		$model = new FeedNews();
		$newsIdData = Yii::$app->request->post();
		if($model->load($newsIdData) && $model->validate()) {
			FeedNews::setNewsRead($model->newsId);
		} else {
			Yii::$app->response->content = Html::encode($model->errors['newsId'][0]);
		}
	}

	/**
	*
	* Удаление прочитанный новостей
	*
	* @access public
	*/
	public function actionDeleteRead() {
		FeedNews::deleteRead();
	}
}