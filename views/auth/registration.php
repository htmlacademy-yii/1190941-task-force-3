<?php

/**
 * @var $model RegistrationForm
 * @var $cities City
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\City;
use app\models\RegistrationForm;

?>

<main class="container container--registration">
	<div class="center-block">
		<div class="registration-form regular-form">
            <?php $form = ActiveForm::begin() ?>
			<h3 class="head-main head-task">Регистрация нового пользователя</h3>
			<?= $form->field($model, 'name'); ?>

			<div class="half-wrapper">
                <?= $form->field($model, 'email')->input('email'); ?>
                <?= $form->field($model, 'city_id')->dropDownList($cities); ?>
			</div>

            <?= $form->field($model, 'password')->input('password'); ?>
            <?= $form->field($model, 'password_retype')->input('password'); ?>
            <?= $form->field($model, 'role_id')->checkbox(); ?>

            <?= Html::submitButton('Создать аккаунт', [
					'class' => 'button button--blue',
			]); ?>
            <?php ActiveForm::end(); ?>
		</div>
	</div>
</main>
