<?php

/**
 * @var $task Task
 * @var $model ResponseAddFrom
 * @var $isCustomer bool
 * @var $isResponded bool
 * @var $action AbstractAction
 */

use yii\helpers\Html;

use app\models\Task;
use app\models\ResponseAddFrom;

use tf\models\Task as TaskHelper;
use tf\models\actions\AbstractAction;
use tf\helpers\DateModifier;

// todo карта +geocoder api
// todo вывести файлы для конкретной задачи

?>
<main class="main-content container">

	<div class="left-column">
		<div class="head-wrapper">
			<h3 class="head-main"><?= Html::encode($task->title); ?></h3>
			<p class="price price--big"><?= $task->price ? Html::encode($task->price) . ' ₽' : 'Бюджет не указан'; ?> </p>
		</div>

		<p class="task-description"><?= Html::encode($task->description); ?></p>

        <?php if ($action): ?>
			<a href="#noscroll" class="button button--blue action-btn"
			   data-action="act_<?= $action->getInnerTitle(); ?>"><?= $action->getTitle() ?></a>
        <?php endif; ?>

		<div class="task-map">
			<img class="map" src="/img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
			<p class="map-address town"><?= Html::encode($task->city->name ?? 'Город не указан'); ?></p>
			<p class="map-address">Новый арбат, 23, к. 1</p>
		</div>

        <?php if ($task->taskResponses): ?>
			<h4 class="head-regular">Отклики на задание</h4>
            <?php foreach ($task->taskResponses as $response): ?>
                <?= Yii::$app->controller->renderPartial('_response', [
                    'response' => $response,
					'taskStatus' => $task->status,
                    'isCustomer' => $isCustomer,
                ]); ?>
            <?php endforeach; ?>
        <?php endif; ?>
	</div>

	<div class="right-column">
		<div class="right-card black info-card">
			<h4 class="head-card">Информация о задании</h4>
			<dl class="black-list">
				<dt>Категория</dt>
				<dd><?= Html::encode($task->category->name); ?></dd>
				<dt>Дата публикации</dt>
				<dd><?= DateModifier::getRelativeFormat($task->created_at, 'назад'); ?></dd>
				<dt>Срок выполнения</dt>
				<dd><?= Yii::$app->formatter->asDatetime($task->deadline, 'php:d F, H:i'); ?></dd>
				<dt>Статус</dt>
				<dd><?= TaskHelper::STATUSES_MAP[$task->status]; ?></dd>
			</dl>
		</div>

        <?php if ($task->taskAttachments): ?>
			<div class="right-card white file-card">
				<h4 class="head-card">Файлы задания</h4>
				<ul class="enumeration-list">
                    <?php foreach ($task->taskAttachments as $attachment): ?>
						<li class="enumeration-item">
							<a href="/uploads/<?= Html::encode($attachment->attachment_path); ?>"
							   class="link link--block link--clip"><?= Html::encode($attachment->attachment_path); ?></a>
							<p class="file-size"><?= Html::encode(Yii::$app->formatter->asShortSize($attachment->size,
                                    0)); ?></p>
						</li>
                    <?php endforeach; ?>
				</ul>
			</div>
        <?php endif; ?>
	</div>

	<?php if ($action): ?>
	<?= Yii::$app->controller->renderPartial('_modal', [
			'action' => $action,
			'task' => $task,
			'model' => $model
		]) ?>
	<?php endif; ?>
</main>
