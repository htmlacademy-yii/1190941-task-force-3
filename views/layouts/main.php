<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap4\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);

$user = Yii::$app->view->params['user'] ?? null;

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="page-header">

	<!-- todo переписать на виджет навигации (если будет время) -->
	<nav class="main-nav">
		<a href='/' class="header-logo">
			<img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
		</a>

        <?php if (Yii::$app->controller->id !== 'auth' && Yii::$app->controller->action->id !== 'registration'): ?>
		<div class="nav-wrapper">
			<ul class="nav-list">
				<li class="list-item list-item--active">
					<a href="<?= Url::to('/tasks'); ?>" class="link link--nav" >Новое</a>
				</li>
				<li class="list-item">
					<a href="#" class="link link--nav" >Мои задания</a>
				</li>
				<?php if (Yii::$app->user->can('customer')): ?>
				<li class="list-item">
					<a href="<?= Url::to('/tasks/add'); ?>" class="link link--nav" >Создать задание</a>
				</li>
				<?php endif; ?>
				<li class="list-item">
					<a href="#" class="link link--nav" >Настройки</a>
				</li>
			</ul>
		</div>
		<?php endif; ?>
	</nav>

    <?php if (Yii::$app->controller->id !== 'auth' && Yii::$app->controller->action->id !== 'registration'): ?>
	<div class="user-block">
		<a href="#">
			<img class="user-photo" src="/img/avatars/<?= Html::encode($user->avatar_name ?? '5.png'); ?>" width="55" height="55" alt="Аватар">
		</a>
		<div class="user-menu">
			<p class="user-name"><?= Html::encode($user->name); ?></p>
			<div class="popup-head">
				<ul class="popup-menu">

					<li class="menu-item">
						<a href="#" class="link">Настройки</a>
					</li>
					<li class="menu-item">
						<a href="#" class="link">Связаться с нами</a>
					</li>
					<li class="menu-item">
						<a href="<?= Url::to('/logout'); ?>" class="link">Выход из системы</a>
					</li>

				</ul>
			</div>
		</div>
	</div>
    <?php endif; ?>
</header>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
