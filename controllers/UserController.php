<?php

namespace app\controllers;

use app\models\User;

class UserController extends SecuredController
{
    public function actionIndex($id): string
    {
        // qstn как добавить связь user_specializations.category_id => categories.id? - userSpecializations.category попробовать
        $user = User::find()
            ->with(['userSpecializations' => function ($query) {
                $query->with('category');
            }, 'performerTasks'])
            ->where(['users.id' => $id])
            ->one();

        // todo Страница предназначена только для показа профилей исполнителей.
        //  Соответственно, если этот пользователь не является исполнителем,
        //  то страница должна быть недоступна: вместо неё надо показывать ошибку 404.

        // todo Блок с контактами показывается всем, только если у пользователя в настройках \
        //  не отмечена опция «Показывать мои контакты только заказчику».
        //  В противном случае, этот блок будет виден только пользователям,
        //  у которых данный исполнитель был назначен на задание.

        return $this->render('index', [
            'user' => $user,
        ]);
    }

    public function actionSettings()
    {
        // todo Отдельная модель для настроек аккаунта пользователя
    }
}
