<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\helpers\Json;
use app\models\FeedUrl;
use app\models\FeedNews;
use rssparser\RssParser;
use yii\base\ErrorException;

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
				foreach ($newsUrls as $newsUrl) {
					//Если ссылка на текущую новую нвоость совпадает с ссылкой одной из новостей, находящихся в базе
					if($newsUrl['news_link'] === $dataOneNews['link']) {
						//Устанавливаем флаг что новость уже есть в базе
						$flagNewsInDb = true;
						break;
					}
				}
				//Если новости нет в базе
				if($flagNewsInDb === false) {
					//Формируем массив со всеми новостями, коотрых еще нет в системе
					$newsList[] = [
						null, 
						$feedData['feed_id'],
						$dataOneNews['title'],
						Json::encode($dataOneNews['description']),
						$dataOneNews['link'],
						$dataOneNews['publicationDate'],
						0
					];
				}
			}
		}
		if(isset($newsList) === true) {
			//Сохраняем новость в базе данных
			FeedNews::saveNews($newsList);
		}
	}

	/**
	*
	* Действие по переносу новостей в архив
	*
	* @access public
	*/
	public function actionNewsToArchive() {
		//Формируем экземпляр модели новостей
		$model = new FeedNews();
		//Получаем от пользователя id новости, которую необходимо отправить в архив
		$newsIdData = Yii::$app->request->post();
		//Если от пользователи пришли данные
		if($newsIdData !== []) {
			//Если данные успешно загрузились в модель
			if($model->load($newsIdData) === true) {
				//Если данные прошли валидацию
				if($model->validate() === true) {
					//Отправляем новосьт в архив
					FeedNews::sendNewsDataToArchive($model->newsId);
				} else {
					throw new ErrorException(Html::encode($model->errors['newsId'][0]));
				}
			} else {
				throw new ErrorException("Ошибка системы");
			}
		} else {
			throw new ErrorException("Ошибка системы");
		}
	}

	/**
	*
	* Действие по удалению новости из архива
	*
	* @access public
	*/
	public function actionDeleteArchiveNews() {
		//Формируем экземпляр модели новостей
		$model = new FeedNews();
		//Получаем от пользователя id новости, которую необходимо удалить из архива
		$newsIdData = Yii::$app->request->post();
		//Если от пользователи пришли данные
		if($newsIdData !== []) {
			//Если данные успешно загрузились в модель
			if($model->load($newsIdData) === true) {
				//Если данные прошли валидацию
				if($model->validate() === true) {
					//Производим удалени новости из архива
					FeedNews::deleteNewsFromArchive($model->newsId);
				} else {
					throw new ErrorException(Html::encode($model->errors['newsId'][0]));
				}
			} else {
				throw new ErrorException("Ошибка системы");
			}
		} else {
			throw new ErrorException("Ошибка системы");
		}
	}

	/**
	*
	* Метод установления флага, обозначающего что новость была прочитана и ее не нужно больше выводить польователю
	*
	* @access public
	*/
	public function actionSetRead() {
		//Формируем экземпляр модели новостей
		$model = new FeedNews();
		//Получаем от пользователя id новости
		$newsIdData = Yii::$app->request->post();
		//Если от пользователи пришли данные
		if($newsIdData !== []) {
			//Если данные успешно загрузились в модель
			if($model->load($newsIdData) === true) {
				//Если данные прошли валидацию
				if($model->validate() === true) {
					//Производим обновление значения флага
					FeedNews::setNewsRead($model->newsId);
				} else {
					throw new ErrorException(Html::encode($model->errors['newsId'][0]));
				}
			} else {
				throw new ErrorException("Ошибка системы");
			}
		} else {
			throw new ErrorException("Ошибка системы");
		}
	}

	/**
	*
	* Удаление прочитанный новостей
	*
	* @access public
	*/
	public function actionDeleteRead() {
		//Производим удаление прочитанных новостей
		FeedNews::deleteRead();
	}
}