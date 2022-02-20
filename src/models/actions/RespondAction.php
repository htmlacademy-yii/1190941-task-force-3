<?php

namespace tf\models\actions;

use app\models\TaskResponse;
use Yii;
use app\models\User;

class RespondAction extends AbstractAction
{
    public const CODE = 'respond';

    public function getTitle(): string
    {
        return 'Откликнуться на задание';
    }

    public function getInnerTitle(): string
    {
        return self::CODE;
    }

    public function checkPermission(int $userID, int $customerID, ?int $performerID): bool
    {
        if (
            Yii::$app->user->can(User::ROLE_PERFORMER)
            && TaskResponse::find()->where([
                'user_id' => Yii::$app->user->getId(),
                'task_id' => Yii::$app->request->get('id')
            ])->one()
        ) {
            return false;
        }

        return true;
    }
}
