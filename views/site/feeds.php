<?php
$this -> title = 'Список фидов';
$this -> registerCssFile('/css/css/style.css');
$this -> registerJsFile('/js/index.js');
?>

		<div class="main">
			<div class="row marg-center">
				<div class="col-md-12 col-sm-12 col-xl-6 inline-block">
					<h1 class="h1-title">Список фидов</h1>
				</div>
			</div>
			<?php if($feedData !== []): ?>
				<?php foreach ($feedData as $dataOneFeed): ?>
					<div class="row marg-center bord-bottom" id="row-<?= $dataOneFeed['feed_id']; ?>">
						<div class="col-md-1 col-sm-1 col-xl-1 inline-block">
						    <img src="/images/bucket-not-hover.png" alt="Отправить в архив" class="bucket cursor-pointer" id="img-bucket-feed-<?= $dataOneFeed['feed_id']; ?>">
						</div>
						<div class="col-md-9 col-sm-9 col-xl-4 inline-block">
							<p class="news-title"><?= $dataOneFeed['feed_url']; ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p class="no-data">Обрабатываемые фиды отсутствуют</p>
			<?php endif; ?>
		</div>