<?php

/**
 * @var $model LoginForm
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\LoginForm;

?>

<section class="modal enter-form form-modal" id="enter-form">
	<h2>Вход на сайт</h2>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'email')->input('email', [
        'class' => 'enter-form-email input input-middle'
    ]) ?>

    <?= $form->field($model, 'password')->input('password', [
        'class' => 'enter-form-password input input-middle'
    ]) ?>

    <?= Html::submitButton('Войти', [
			'class' => 'button'
	]) ?>
    <?php ActiveForm::end(); ?>
	<button class="form-modal-close" type="button">Закрыть</button>
</section>
