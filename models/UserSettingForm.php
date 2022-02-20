<?php

namespace app\models;

use yii\base\Model;

class UserSettingForm extends Model
{
    // todo Поля «Имя» и «Email» обязательны для заполнения
    // todo Значение поля «День рождения» должно быть валидной датой в формате дд.мм.гггг.
    // todo Значение поля «Номер телефона» должно быть строкой из чисел длиной в 11 символов.
    // todo Значение поля «Telegram» должно быть строкой до 64 символов.
    // todo В выборе специализаций отмечаются категории заданий, в которых пользователь работает исполнителем.

    // todo Безопасность https://up.htmlacademy.ru/profession/backender/1/yii/3/project/task-force#:~:text=%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%20%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%B0%D0%B5%D1%82%20%D0%B8%D1%81%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D0%B5%D0%BC.-,%D0%91%D0%B5%D0%B7%D0%BE%D0%BF%D0%B0%D1%81%D0%BD%D0%BE%D1%81%D1%82%D1%8C,-%D0%92%C2%A0%D1%8D%D1%82%D0%BE%D0%B9%20%D1%81%D0%B5%D0%BA%D1%86%D0%B8%D0%B8
}
