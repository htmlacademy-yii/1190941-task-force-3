<?php

/**
 * @var $model Task
 */

use yii\helpers\Html;
use app\models\Task;
use tf\helpers\DateModifier;
use yii\helpers\Url;

?>
<div class="task-card">
	<div class="header-task">
		<a href="<?= Url::to("/tasks/view/{$model->id}") ?>" class="link link--block link--big"><?= Html::encode($model->title) ?></a>
		<p class="price price--task"><?= $model->price ? Html::encode($model->price) . ' ₽' : 'Бюджет не указан' ?> </p>
	</div>
	<p class="info-text">
					<span class="current-time"><?= DateModifier::getRelativeFormat($model->created_at,
                            'назад'); ?></span>
	</p>
	<p class="task-text"><?= Html::encode($model->description) ?></p>
	<div class="footer-task">
		<p class="info-text town-text"><?= !($model->city === null) ? Html::encode($model->city->name)
                : 'Город не указан'; ?></p>
		<p class="info-text category-text"><?= Html::encode($model->category->name) ?></p>
		<a href="<?= Url::to("/tasks/view/{$model->id}") ?>" class="button button--black">Смотреть Задание</a>
	</div>
</div>
