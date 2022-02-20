<?php

namespace app\models;

use Yii;
use yii\base\Model;

// todo убрать 0 из статусов в БД
// todo Вход/регистрация через «ВК» https://up.htmlacademy.ru/profession/backender/1/yii/3/project/task-force#:~:text=%D0%92%D1%85%D0%BE%D0%B4/%D1%80%D0%B5%D0%B3%D0%B8%D1%81%D1%82%D1%80%D0%B0%D1%86%D0%B8%D1%8F%20%D1%87%D0%B5%D1%80%D0%B5%D0%B7%20%C2%AB%D0%92%D0%9A%C2%BB

class RegistrationForm extends Model
{
    public $name;
    public $email;
    public $city_id;
    public $password;
    public $password_retype;
    public $role_id;

    private const ROLE_ID_TO_NAME_MAP = [
        0 => 'customer',
        1 => 'performer'
    ];

    public function attributeLabels(): array
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'password_retype' => 'Повтор пароля',
            'role_id' => 'Я собираюсь откликаться на заказы',
        ];
    }

    public function rules(): array
    {
        return [
            [['name', 'email', 'city_id', 'password', 'password_retype', 'role_id'], 'safe'],
            [['name', 'email', 'city_id', 'password', 'password_retype', 'role_id'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['password_retype', 'compare', 'compareAttribute' => 'password'],
            ['city_id', 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => 'id']
        ];
    }

    public function save()
    {
        $newUser = new User();
        $newUser->name = $this->name;
        $newUser->email = $this->email;
        $newUser->password = $this->password;
        $newUser->city_id = $this->city_id;

        $newUser->save();
        $this->addRole($this->role_id, $newUser->id);
    }

    private function addRole($roleID, $userID)
    {
        Yii::$app
            ->authManager
            ->assign(Yii::$app
                ->authManager
                ->getRole(self::ROLE_ID_TO_NAME_MAP[$roleID]), $userID);
    }
}
