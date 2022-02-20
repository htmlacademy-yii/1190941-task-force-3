<?php

/**
 * @var $user User
 */

use yii\helpers\Url;
use yii\helpers\Html;

use app\models\User;

use tf\models\Task as TaskHelper;
use tf\helpers\DateModifier;

// qstn может эту логику вынести в контроллер?
$userRating = User::getRating($user->userReviews, $user->id);
$userRank = User::getRank($user->id);

?>

<main class="main-content container">
	<div class="left-column">
		<h3 class="head-main"><?= Html::encode($user->name) ?></h3>
		<div class="user-card">
			<div class="photo-rate">
				<img class="card-photo" src="/img/avatars/<?= Html::encode($user->avatar_name ?? '5.png') ?>" width="191" height="190" alt="Фото пользователя">
				<div class="card-rate">
					<div class="stars-rating big">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= round($userRating)): ?>
								<span class="fill-star">&nbsp;</span>
                            <?php else: ?>
								<span>&nbsp;</span>
                            <?php endif; ?>
                        <?php endfor; ?>
					</div>
					<span class="current-rate"><?= round(($userRating), 1) ?></span>
				</div>
			</div>
			<p class="user-description"><?= Html::encode($user->about ?? 'Пользователь не делился информацией о себе.') ?></p>
		</div>
		<div class="specialization-bio">
            <?php if ($user->userSpecializations): ?>
				<div class="specialization">
					<p class="head-info">Специализации</p>
					<ul class="special-list">
                        <?php foreach ($user->userSpecializations as $specialization): ?>
							<li class="special-item">
								<a href="<?= Url::to("/tasks/index?TaskFilterForm%5Bcategories%5D=&TaskFilterForm%5Bcategories%5D%5B%5D={$specialization->category_id}") ?>" class="link link--regular"><?= Html::encode($specialization->category->name); ?></a>
							</li>
                        <?php endforeach; ?>
					</ul>
				</div>
            <?php endif; ?>

			<div class="bio">
				<p class="head-info">Био</p>
				<p class="bio-info">
					<span class="country-info"><?= Html::encode($user->city->name); ?></span>
<!--					<span class="town-info">Петербург</span>-->
					,
					<span class="age-info"><?= $user->date_of_birth
							? DateModifier::getRelativeFormat($user->date_of_birth)
							: 'Не указано'; ?></span>
				</p>
			</div>
		</div>

        <?php if ($user->userReviews): ?>
			<h4 class="head-regular">Отзывы заказчиков</h4>

            <?php foreach ($user->userReviews as $review): ?>
				<div class="response-card">
					<img class="customer-photo" src="/img/avatars/<?= Html::encode($review->reviewer->avatar_name) ?>" width="120" height="127" alt="Фото заказчиков">
					<div class="feedback-wrapper">
						<p class="feedback">«<?= Html::encode($review->review) ?>»</p>
						<p class="task">Задание «
							<a href="<?= Url::to("/tasks/view/{$review->task_id}") ?>" class="link link--small"><?= Html::encode($review->task->title) ?></a>
										» выполнено
						</p>
					</div>
					<div class="feedback-wrapper">
						<div class="stars-rating small">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $review->rating): ?>
									<span class="fill-star">&nbsp;</span>
                                <?php else: ?>
									<span>&nbsp;</span>
                                <?php endif; ?>
                            <?php endfor; ?>
						</div>
						<p class="info-text">
							<span class="current-time">
								<?= DateModifier::getRelativeFormat($review->created_at, 'назад'); ?>
							</span>
						</p>
					</div>
				</div>
            <?php endforeach; ?>
        <?php endif; ?>
	</div>

	<div class="right-column">
		<div class="right-card black">
			<h4 class="head-card">Статистика исполнителя</h4>
			<dl class="black-list">
				<dt>Всего заказов</dt>
				<dd><?= count(array_filter($user->performerTasks, function ($task) {
                        return $task->status === TaskHelper::STATUS_DONE;
                    })) ?> выполнено, <?= count($user->refusalReasons); ?> провалено</dd>
				<dt>Место в рейтинге</dt>
				<dd><?= Html::encode($userRank); ?> место</dd>
				<dt>Дата регистрации</dt>
				<dd><?= DateModifier::getDateTime($user->created_at, 'd F, H:i'); ?></dd>
				<dt>Статус</dt>
				<dd><?= User::STATUSES_MAP[$user->status]; ?></dd>
			</dl>
		</div>
		<div class="right-card white">
			<h4 class="head-card">Контакты</h4>
			<ul class="enumeration-list">
				<?php if ($user->phone): ?>
				<li class="enumeration-item">
					<a href="tel:7<?= Html::encode($user->phone); ?>" class="link link--block link--phone">+7<?= Html::encode($user->phone); ?></a>
				</li>
				<?php endif; ?>
                <?php if ($user->email): ?>
				<li class="enumeration-item">
					<a href="mailto:<?= Html::encode($user->email); ?>" class="link link--block link--email"><?= Html::encode($user->email); ?></a>
				</li>
                <?php endif; ?>
                <?php if ($user->telegram): ?>
				<li class="enumeration-item">
					<a href="#" class="link link--block link--tg">@<?= Html::encode($user->telegram); ?></a>
				</li>
                <?php endif; ?>
			</ul>
		</div>
	</div>
</main>
