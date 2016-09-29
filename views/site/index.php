<?php

use yii\helpers\Json;
use yii\helpers\Html;

$this -> title = 'Новости';
$this -> registerCssFile('/css/css/style.css');
$this -> registerJsFile('/js/index.js');
?>
		<div class="main">
			<div class="row marg-center">
				<div class="col-md-12 col-sm-12 col-xl-6 inline-block">
					<h1 class="h1-title">Новости</h1>
				</div>
			</div>
			<?php if($newsData !== []): ?>
				<?php foreach($newsData as $oneNews): ?>
					<div class="row marg-center bord-bottom" id="row-<?= $oneNews['news_id']; ?>">
						<div class="col-md-1 col-sm-1 col-xl-1 inline-block">
						    <img src="/images/folder-not-active.png" alt="Отправить в архив" class="folder cursor-pointer" id="img-folder-<?= $oneNews['news_id']; ?>">
						</div>
						<div class="col-md-9 col-sm-9 col-xl-4 inline-block cursor-pointer" id="title-<?= $oneNews['news_id']; ?>">
							<p class="news-title"><?= $oneNews['news_title']; ?></p>
						</div>
						<div class="col-md-2 col-sm-2 col-xl-1 inline-block cursor-pointer" id="date-<?= $oneNews['news_id']; ?>">
							<p class="news-date">
								<?php 
									$date = new \DateTime();
									$date->setTimestamp($oneNews['publication_date']);
									echo $date->format('Y.m.d'); 
								?>			
							</p>
						</div>
					</div>
					<div class="row marg-center bord-bottom not-display" id="description-<?= $oneNews['news_id']; ?>">
						<div class="col-md-12 col-sm-12 col-xl-6 inline-block cursor-pointer">
							<p class="news-description">
								<?php
									$newsDescription = Json::decode($oneNews['news_description']);
									foreach ($newsDescription as $descriptionParagraph) {
										echo Html::decode($descriptionParagraph);
									}
								?>
							</p>
							<a href="<?= $oneNews['news_link']; ?>" rel="nofollow" target="_blanck" class="all-news-text inline-block">Читать далее</a>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p class="no-data">Новые новости отсутствуют</p>
			<?php endif; ?>
		</div>