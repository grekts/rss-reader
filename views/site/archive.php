<?php

use yii\helpers\Json;

$this -> title = 'Архив новостей';
$this -> registerCssFile('/css/css/style.css');
$this -> registerJsFile('/js/index.js');
?>

		<div class="main">
			<div class="row marg-center">
				<div class="cols-12 cols-6 inline-block">
					<h1 class="h1-title">Архив новостей</h1>
				</div>
			</div>
			<?php if($newsData !== []): ?>
				<?php foreach ($newsData as $oneNews): ?>
					<div class="row marg-center bord-bottom" id="row-<?= $oneNews['news_archive_id']; ?>">
						<div class="cols-1 inline-block">
						    <img src="/images/bucket-not-hover.png" alt="Отправить в архив" class="bucket cursor-pointer" id="img-bucket-news-<?= $oneNews['news_archive_id']; ?>">
						</div>
						<div class="cols-9 cols-4 inline-block cursor-pointer" id="title-<?= $oneNews['news_archive_id']; ?>">
							<p class="news-title"><?= $oneNews['news_title']; ?></p>
						</div>
						<div class="cols-2 cols-1 inline-block cursor-pointer" id="date-<?= $oneNews['news_archive_id']; ?>">
							<p class="news-date">
								<?php 
									$date = new \DateTime();
									$date->setTimestamp($oneNews['publication_date']);
									echo $date->format('Y.m.d'); 
								?>
							</p>
						</div>
					</div>
					<div class="row marg-center bord-bottom not-display cursor-pointer" id="description-<?= $oneNews['news_archive_id']; ?>">
						<div class="cols-12 cols-6 inline-block">
							<p class="news-description">
								<?php
									$newsDescription = Json::decode($oneNews['news_description']);
									foreach ($newsDescription as $descriptionParagraph) {
										echo $descriptionParagraph;
									}
								?>
							</p>
							<a href="<?= $oneNews['news_link']; ?>" rel="nofollow" target="_blanck" class="all-news-text inline-block">Читать далее</a>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p class="no-data">Архив новостей пуст</p>
			<?php endif; ?>
		</div>