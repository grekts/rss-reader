<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;
use yii\base\UserException;
use yii\helpers\Json;
use yii\db\Exception;

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
        	['newsId', 'trim'],
            ['newsId', 'required', 'message' => 'Не указан идентификатор новости'],
            ['newsId', 'integer', 'message' => 'Тип идентификатора новости не соответсвует требуемому'],
        ];
    }


	/**
	*
	* Метод сохранения в базе новостей из фида
	*
	* @param array $newsData Масив с данными одной новости из фида
	* @access public
	* @static
	*
	*/
	public static function saveNews($newsData) {
		try {
			Yii::$app->db->createCommand()->batchInsert(
				'news', 
				[
					'news_id', 
					'feed_id', 
					'news_title', 
					'news_description', 
					'news_link', 
					'publication_date', 
					'read_news'
				], $newsData)->execute();
		} catch (Exeption $e) {
			throw new ErrorException("Ошибка системы");
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
        } catch (Exeption $e) {
            throw new ErrorException("Ошибка системы");
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
        } catch (Exeption $e) {
            throw new ErrorException("Ошибка системы");
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
        } catch (Exeption $e) {
            throw new ErrorException("Ошибка системы");
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
		//Получаем данные новости, которую нужно отправить в архив
		try {
			$newsData = Yii::$app->db->createCommand('SELECT feed_id, news_title, news_description, news_link, publication_date FROM news WHERE news_id = :newsId')
			->bindValue(':newsId', $newsId)
			->queryOne();
		} catch (Exeption $e) {
			throw new ErrorException("Ошибка системы");
		}
		//Если новость с полученным id есть в базе
		if($newsData !== false) {
			//Пытаемя получить из архива новость со ссылкой, равной ссылки обрабатываемой новости
			try {
				$idNewsInArchive = Yii::$app->db->createCommand('SELECT news_archive_id FROM news_archive WHERE news_link = :newsLink')
				->bindValue(':newsLink', $newsData['news_link'])
				->queryOne();
			} catch (Exeption $e) {
				throw new ErrorException("Ошибка системы");
			}
			//Если новость не была ранее добавлена в архив
			if($idNewsInArchive === false) {
				//Отправляем новость в архив
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
				} catch (Exeption $e) {
					throw new ErrorException("Ошибка системы");
				}
			} else {
				throw new UserException("Данная новость уже находится в архиве");
			}
		} else {
			throw new ErrorException("Ошибка системы");
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
		try {
            Yii::$app->db->createCommand('DELETE FROM news_archive WHERE news_archive_id = :id')
                ->bindValue(':id', $newsId)
                ->execute();

            Yii::$app->response->content = 'Новость удалена из архива';
        } catch (Exception $e) {
            throw new ErrorException("Ошибка системы");
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
		try {
            Yii::$app->db->createCommand('UPDATE news SET read_news = :flagRead WHERE news_id = :id')
                ->bindValues([':flagRead' => true, ':id' => $newsId])
                ->execute();
        } catch (Exception $e) {
            throw new ErrorException("Ошибка системы");
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
        } catch (Exception $e) {
            throw new ErrorException("Ошибка системы");
        }
	}
}