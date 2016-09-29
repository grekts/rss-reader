<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use yii\widgets\ActiveForm;
use app\components\sendUrlForm\SendUrlFormWidget;
use app\components\showMessage\ShowMessageWidget;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<title><?= Html::encode($this->title) ?></title>
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
	<div class="container">
		<header class="header">
		    <div class="row marg-center header-height">
			    <div class="col-md-2 col-sm-2 col-xl-6 pos-relative inline-block">
			    	<a href="/" class="logo">RSS</a>
			    </div>
			    <?= ShowMessageWidget::widget() ?>
			    <div class="col-md-5 col-sm-5 col-xl-6 inline-block">
		    	<?= 
		    		Menu::widget([
					    'items' => [
					        ['label' => 'Список фидов', 'url' => ['feeds']],
					        ['label' => 'Архив новостей', 'url' => ['archive']],
					    ],
					    'options' => [
							'class' => 'main-menu',
						],
						'itemOptions' => [
							'class' => 'element-main-menu',
						],
						'linkTemplate' => '<a href="{url}" class="menu-text">{label}</a>',
					]);
		    	?>
		    	<div class="main-menu-button not-display cursor-pointer">≡</div>
			    </div>
			    <div class="col-md-5 col-sm-5 col-xl-6 pos-relative inline-block">
			    	<?= SendUrlFormWidget::widget() ?>
			    </div>
			</div>
		</header>
		<?= $content ?>
		<footer class="footer">
			<div class="row marg-center">
				<div class="col-md-12 col-sm-12 col-xl-6 inline-block">
					<p class="copyright">&#169; <?= date('Y'); ?></p>
				</div>
			</div>
		</footer>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>