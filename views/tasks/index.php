<?php

/**
 * @var $this yii\web\View
 * @var $dataProvider ActiveDataProvider
 * @var $model TaskFilterForm
 * @var $categories Category
 */

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Category;
use app\models\TaskFilterForm;

use yii\widgets\ListView;

$this->title = 'Новые задания';
?>

<main class="main-content container">
	<div class="left-column">
		<h3 class="head-main head-task">Новые задания</h3>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_preview',
            'emptyText' => 'По вашему запросу не найдено ни одной записи',
            'pager' => [
                'activePageCssClass' => 'pagination-item--active',
                'pageCssClass' => 'pagination-item',
                'prevPageCssClass' => 'pagination-item mark',
                'nextPageCssClass' => 'pagination-item mark',
                'prevPageLabel' => '',
                'nextPageLabel' => '',
                'linkOptions' => [
                    'class' => 'link link--page'
                ],
                'options' => [
                    'class' => 'pagination-list'
                ]
            ]
        ]) ?>
	</div>

	<div class="right-column">
		<div class="right-card black">
			<div class="search-form">
                <?php $form = ActiveForm::begin([
                    'action' => '/tasks/index', // todo смотреть на строку запроса, почему /tasks не достаточно?
                    'id' => null,
                    'method' => 'get',
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => false,
                    'fieldConfig' => [
                        'template' => "{input}",
                    ]
                ]); ?>

				<h4 class="head-card">Категории</h4>
                <?= $form->field($model, 'categories', [
                    'template' => "{input}",
                    'addAriaAttributes' => false,
                ])
                    ->checkboxList($categories, [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return Html::checkbox($name, $checked,
                                    ['value' => $value, 'id' => "category-id-{$value}"])
                                . "<label class=\"control-label\" for=\"category-id-{$value}\">" . $label . '</label>';
                        }
                    ]); ?>

				<h4 class="head-card">Дополнительно</h4>
                <?= $form->field($model, 'withoutResponses')->checkbox(); ?>
                <?= $form->field($model, 'remoteWork')->checkbox(); ?>

				<h4 class="head-card">Период</h4>
                <?= $form->field($model, 'period')->dropDownList(TaskFilterForm::PERIOD_MAP); ?>

                <?= Html::button('Искать', [
                    'type' => 'submit',
                    'class' => 'button button--blue'
                ]) ?>

                <?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</main>
