<?php

/**
 * @var $action AbstractAction
 * @var $task Task
 * @var $model ResponseAddFrom|UserReviewAddForm
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use app\models\ResponseAddFrom;
use app\models\UserReviewAddForm;
use app\models\Task;

use tf\models\actions\AbstractAction;

?>

<div class="overlay">
    <?php if ($action->getInnerTitle() === 'respond'): ?>
		<section class="pop-up pop-up--act_respond pop-up--close">
            <?php $form = ActiveForm::begin([
                'action' => "/response/add/{$task->id}"
            ]) ?>
			<h2>Оставьте отклик</h2>
            <?= Html::button('', [
                'type' => 'button',
                'class' => 'button--close'
            ]) ?>
            <?= $form->field($model, 'budget')->input('number'); ?>
            <?= $form->field($model, 'comment')->textarea(); ?>
            <?= Html::submitButton('Отправить') ?>
            <?php $form::end(); ?>
		</section>
    <?php elseif ($action->getInnerTitle() === 'cancel'): ?>
		<section class="pop-up pop-up--act_cancel pop-up--close">
			<h2>Уверены что хотите отменить задание?</h2>
            <?= Html::button('', [
                'type' => 'button',
                'class' => 'button--close'
            ]) ?>
			<a href="<?= Url::to("/tasks/cancel/{$task->id}") ?>">Да</a>
		</section>
    <?php elseif ($action->getInnerTitle() === 'accept'): ?>
		<section class="pop-up pop-up--act_accept pop-up--close">
            <?php $form = ActiveForm::begin([
                'action' => "/tasks/accept/{$task->id}"
            ]) ?>
			<h2>Оцените исполнителя:</h2>
            <?= Html::button('', [
                'type' => 'button',
                'class' => 'button--close'
            ]) ?>
            <?= $form->field($model, 'review')->textarea(); ?>
            <?= $form->field($model, 'rating')->hiddenInput(); ?>
			<div class="stars-rating active-stars small">
                <?php for ($i = 0; $i < 5; $i++): ?>
					<span></span>
                <?php endfor; ?>
			</div>
            <?= Html::submitButton('Завершить'); ?>
            <?php $form->end(); ?>
		</section>
    <?php elseif ($action->getInnerTitle() === 'refuse'): ?>
	<section class="pop-up pop-up--act_refuse pop-up--close">
        <?php $form = ActiveForm::begin([
            'action' => "/tasks/refuse/{$task->id}"
        ]) ?>
        <?= Html::button('', [
            'type' => 'button',
            'class' => 'button--close'
        ]) ?>
		<h2>Почему я не смог</h2>
        <?= $form->field($model, 'comment')->textarea(); ?>
        <?= Html::submitButton('Покинуть корабль') ?>
        <?php $form->end(); ?>
	</section>
    <?php endif; ?>
</div>
