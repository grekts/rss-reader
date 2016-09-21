<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;
use yii\helpers\Json;

/**
*
* Модель новостей фида
*
* @author Roman Tsutskov
*
*/
class FeedNews extends Model
{
	/**
	 * Id фида в базе данных
	 * 
	 * @var string
	*/
    public $newsId;

    /**
    *
    * Метод опредеяет правило валидации id фида
    *
    * @access public
    */
    public function rules()
    {
        return [
            ['newsId', 'required', 'message' => 'Не указан идентификатор новости'],
            ['newsId', 'integer', 'message' => 'Тип идентификатора новости не соответсвует требуемому'],
        ];
    }


	/**
	*
	* Метод сохранения в базе новостей из фида
	*
	* @param array $newsData Масив с данными одной новости из фида
	* @param integer $feedId Взятый из базыданных id фида, которому принадлежит новость
	* @access public
	* @static
	*
	*/
	public static function saveNews($newsData, $feedId) {
		$dataType1 = gettype($newsData);
		$dataType2 = gettype($feedId);
		if(($dataType1 === 'array') && ($dataType2 === 'string') && ($newsData !=='') && ($feedId !== '')) {
			try {
				Yii::$app->db->createCommand('INSERT INTO news VALUES (:id, :feedId, :title, :description, :link, :publicationDate, :reedFlag)')
				->bindValues([
					':id' => null, 
					':feedId' => $feedId, 
					':title' => $newsData['title'], 
					':description' => Json::encode($newsData['description']), 
					':link' => $newsData['link'], 
					':publicationDate' => $newsData['publicationDate'],
					':reedFlag' => 0
				])
				->execute();
			} catch (ErrorExeption $e) {
				throw new ErrorException("Ошибка отправке новостей в базу данных");
			}
		} else {
			if($dataType1 !== 'array') {
				throw new ErrorException("Тип данных входного параметра не соответствует типу array");
			}
			if($dataType2 !== 'string') {
				throw new ErrorException("Тип данных входного параметра не соответствует типу string");
			}
			if($newsData === '') {
				throw new ErrorException("Не указаны данные во входном параметре");
			}
			if($feedId === '') {
				throw new ErrorException("Не указаны данные во входном параметре");
			}
		}
	}

	/**
	*
	* Метод получения из базы ссылок на содержащиеся в нем новости
	*
	* @access public
	* @static
	*
	*/
	public static function getNewsUrl() {
		try {
            return Yii::$app->db->createCommand('SELECT news_link FROM news')->queryAll();
        } catch (ErrorExeption $e) {
            throw new ErrorException("Ошибка при получении списка ссылок на сохраненные новости");
        }
	}

	/**
	*
	* Метод получения новостей для вывода их на странице сайта
	*
	* @access public
	* @static
	*
	*/
	public static function getNewsData() {
		try {
            return Yii::$app->db->createCommand('SELECT news_id, news_title, news_description, news_link, publication_date FROM news WHERE read_news = 0')->queryAll();
        } catch (ErrorExeption $e) {
            throw new ErrorException("Ошибка при получении из базы данных списка новостей");
        }
	}

	/**
	*
	* Метод получения новостей из архива для вывода их на странице сайта
	*
	* @access public
	* @static
	*
	*/
	public static function getNewsDataFromArchive() {
		try {
            return Yii::$app->db->createCommand('SELECT news_archive_id, news_title, news_description, news_link, publication_date FROM news_archive')->queryAll();
        } catch (ErrorExeption $e) {
            throw new ErrorException("Ошибка при получении из базы данных списка новостей");
        }
	}

	/**
	*
	* Метод отправки новости в архив
	*
	* @access public
	* @static
	*
	*/
	public static function sendNewsDataToArchive($newsId) {
		$dataType = gettype($newsId);
		if(($dataType === 'string') && ($newsId !=='')) {
			try {
				$newsData = Yii::$app->db->createCommand('SELECT feed_id, news_title, news_description, news_link, publication_date FROM news WHERE news_id = :newsId')
				->bindValue(':newsId', $newsId)
				->queryOne();
			} catch (ErrorExeption $e) {
				throw new ErrorException("Ошибка при получении новости для ее переноса в архив");
			}
			//Если новость с полученным id есть в базе
			if($newsData !== false) {
				try {
					$idNewsInArchive = Yii::$app->db->createCommand('SELECT news_archive_id FROM news_archive WHERE news_link = :newsLink')
					->bindValue(':newsLink', $newsData['news_link'])
					->queryOne();
				} catch (ErrorExeption $e) {
					throw new ErrorException("Ошибка при получении id новости при проверке ее наличия в архиве");
				}
				//Если новость не была ранее добавлена в архив
				if($idNewsInArchive === false) {
					try {
						Yii::$app->db->createCommand('INSERT INTO news_archive VALUES (:id, :feedId, :title, :description, :link, :publicationDate)')
						->bindValues([
							':id' => null, 
							':feedId' => $newsData['feed_id'], 
							':title' => $newsData['news_title'], 
							':description' => $newsData['news_description'], 
							':link' => $newsData['news_link'], 
							':publicationDate' => $newsData['publication_date']
						])
						->execute();

						Yii::$app->response->content = 'Новость перенесена в архив';
					} catch (ErrorExeption $e) {
						throw new ErrorException("Ошибка при отправке новости в архив");
					}
				} else {
					Yii::$app->response->content = 'Данная новость уже находится в архиве';
				}
			} else {
				Yii::$app->response->content = 'Указанная новость не найдена в базе';
			}
		} else {
			if($dataType1 !== 'string') {
				throw new ErrorException("Тип данных входного параметра не соответствует типу string");
			}
			if($newsData === '') {
				throw new ErrorException("Не указаны данные во входном параметре");
			}
		}
	}


	/**
	*
	* Метод удаления новости из архива
	*
	* @access public
	* @static
	*
	*/
	public static function deleteNewsFromArchive($newsId) {
		$dataType = gettype($newsId);
		if(($dataType === 'string') && ($newsId !=='')) {
			try {
                Yii::$app->db->createCommand('DELETE FROM news_archive WHERE news_archive_id = :id')
                    ->bindValue(':id', $newsId)
                    ->execute();
                    Yii::$app->response->content = 'Новость удалена из архива';
            } catch (ErrorException $e) {
                throw new ErrorException("Ошибка при отправке данных фида в базу");
            }
		} else {
			if($dataType1 !== 'string') {
				throw new ErrorException("Тип данных входного параметра не соответствует типу string");
			}
			if($newsData === '') {
				throw new ErrorException("Не указаны данные во входном параметре");
			}
		}
	}

	/**
	*
	* Метод установки флага, обозначающего что новость прочитана и ее не нужно больше показывать
	*
	* @access public
	* @static
	*
	*/
	public static function setNewsRead($newsId) {
		$dataType = gettype($newsId);
		if(($dataType === 'string') && ($newsId !=='')) {
			try {
                Yii::$app->db->createCommand('UPDATE news SET read_news = :flagRead WHERE news_id = :id')
                    ->bindValues([':flagRead' => true, ':id' => $newsId])
                    ->execute();
            } catch (ErrorException $e) {
                throw new ErrorException("Ошибка при определении новости как прочитанной");
            }
		} else {
			if($dataType1 !== 'string') {
				throw new ErrorException("Тип данных входного параметра не соответствует типу string");
			}
			if($newsData === '') {
				throw new ErrorException("Не указаны данные во входном параметре");
			}
		}
	}

	/**
	*
	* Метод удаления прочитанных новостей
	*
	* @access public
	* @static
	*
	*/
	public static function deleteRead() {
		try {
            Yii::$app->db->createCommand('DELETE FROM news WHERE read_news = 1')
                ->execute();
        } catch (ErrorException $e) {
            throw new ErrorException("Ошибка при удалении прочитанных новостей");
        }
	}
}