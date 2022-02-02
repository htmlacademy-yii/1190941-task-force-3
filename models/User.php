<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $status
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $created_at
 * @property string $last_action_time
 * @property string|null $avatar_name
 * @property string|null $date_of_birth
 * @property string|null $phone
 * @property string|null $telegram
 * @property string|null $about
 * @property int $city_id
 * @property int $role_id
 *
 * @property City $city
 * @property RefusalReason[] $refusalReasons
 * @property TaskResponse[] $taskResponses
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property UserReview[] $userReviews
 * @property UserSetting[] $userSettings
 * @property UserSpecialization[] $userSpecializations
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'name', 'email', 'password', 'city_id', 'role_id'], 'required'],
            [['created_at', 'last_action_time', 'date_of_birth'], 'safe'],
            [['about'], 'string'],
            [['city_id', 'role_id'], 'integer'],
            [['status'], 'string', 'max' => 45],
            [['name', 'email', 'password', 'avatar_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['telegram'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['avatar_name'], 'unique'],
            [['phone'], 'unique'],
            [['telegram'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'created_at' => 'Created At',
            'last_action_time' => 'Last Action Time',
            'avatar_name' => 'Avatar Name',
            'date_of_birth' => 'Date Of Birth',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'about' => 'About',
            'city_id' => 'City ID',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[RefusalReasons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefusalReasons()
    {
        return $this->hasMany(RefusalReason::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[TaskResponses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskResponses()
    {
        return $this->hasMany(TaskResponse::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::className(), ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[UserReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserReviews()
    {
        return $this->hasMany(UserReview::className(), ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[UserSettings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasMany(UserSetting::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserSpecializations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSpecializations()
    {
        return $this->hasMany(UserSpecialization::className(), ['user_id' => 'id']);
    }
}
