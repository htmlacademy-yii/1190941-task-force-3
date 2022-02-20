<?php

/**
 * @var $model Task
 * @var $categories Category
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;
use app\models\Task;

?>

<main class="main-content main-content--center container">
	<div class="add-task-form regular-form">
        <?php $form = ActiveForm::begin([
				'options' => [
                    'enctype' => 'multipart/form-data'
				]
		]); ?>
		<h3 class="head-main head-main">Публикация нового задания</h3>
		<?= $form->field($model, 'title'); ?>
		<?= $form->field($model, 'description')->textarea(); ?>
		<?= $form->field($model, 'category_id')->dropDownList($categories); ?>
		<!--<div class="form-group">
			<label class="control-label" for="location">Локация</label>
			<input id="location" type="text">
		</div>-->
		<div class="half-wrapper">
            <?= $form->field($model, 'price')->input('number'); ?>
            <?= $form->field($model, 'deadline')->input('date'); ?>
		</div>
		<?= $form->field($model, 'files[]')->fileInput([
				'multiple' => true
		]); ?>
		<?= Html::submitButton('Опубликовать', [
				'class' => 'button button--blue'
		]); ?>
        <?php ActiveForm::end(); ?>
	</div>
</main>
