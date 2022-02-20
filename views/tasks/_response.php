<?php

/**
 * @var $response TaskResponse
 * @var $taskStatus TaskResponse
 * @var $isCustomer bool
 */

use yii\helpers\Url;
use yii\helpers\Html;

use app\models\User;
use app\models\TaskResponse;

use tf\models\Task as TaskHelper;
use tf\helpers\DateModifier;

// qstn могу это поднять на уровень выше?
$performerRating = User::getRating($response->user->userReviews, $response->user->id);

?>

<div class="response-card">
	<img class="customer-photo"
		 src="/img/avatars/<?= Html::encode($response->user->avatar_name ?? '5.png'); ?>"
		 width="146"
		 height="156"
		 alt="Фото заказчиков <?= Html::encode($response->user->name); ?>">

	<div class="feedback-wrapper">
		<a href="<?= Url::to("/user/{$response->user_id}") ?>" class="link link--block link--big"><?= Html::encode($response->user->name); ?></a>
		<div class="response-wrapper">
			<div class="stars-rating small">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= round($performerRating)): ?>
						<span class="fill-star">&nbsp;</span>
                    <?php else: ?>
						<span>&nbsp;</span>
                    <?php endif; ?>
                <?php endfor; ?>
			</div>
			<p class="reviews">
                <?= count($response->user->userReviews); ?>
                <?= DateModifier::getNounPluralForm(count($response->user->userReviews),
                    'отзыв',
                    'отзыва',
                    'отзывов'); ?>
			</p>
		</div>
		<p class="response-message"><?= Html::encode($response->comment) ?></p>
	</div>

	<div class="feedback-wrapper">
		<p class="info-text">
			<span class="current-time">
				<?= DateModifier::getRelativeFormat($response->created_at, 'назад') ?>
			</span>
		</p>
		<p class="price price--small"><?= Html::encode($response->price); ?> ₽</p>
	</div>

	<?php if ($isCustomer && $response->status !== 0 && $taskStatus === TaskHelper::STATUS_NEW): ?>
	<div class="button-popup">
		<a href="<?= Url::to("/response/accept/{$response->id}") ?>"
		   class="button button--blue button--small">Принять</a>
		<a href="<?= Url::to("/response/refuse/{$response->id}") ?>"
		   class="button button--orange button--small">Отказать</a>
	</div>
	<?php endif; ?>
</div>
