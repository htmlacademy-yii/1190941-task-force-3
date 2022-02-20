<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
 * @property Task[] $customerTasks
 * @property Task[] $performerTasks
 * @property UserReview[] $userReviews
 * @property UserSetting[] $userSettings
 * @property UserSpecialization[] $userSpecializations
 * @property string $USER [char(32)]
 * @property int $CURRENT_CONNECTIONS [bigint]
 * @property int $TOTAL_CONNECTIONS [bigint]
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_FREE = 0;
    public const STATUS_BUSY = 1;

    public const STATUSES_MAP = [
        self::STATUS_FREE => 'Открыт для новых заказов',
        self::STATUS_BUSY => 'Занят',
    ];

    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_PERFORMER = 'performer';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'password', 'city_id'], 'required'],
            [['created_at', 'last_action_time', 'date_of_birth'], 'safe'],
            [['about'], 'string'],
            [['city_id', 'role_id'], 'integer'],
            [['role_id'], 'default', 'value' => 0],
            [['status'], 'string', 'max' => 45],
            [['name', 'email', 'password', 'avatar_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['telegram'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['avatar_name'], 'unique'],
            [['phone'], 'unique'],
            [['telegram'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[RefusalReasons]].
     *
     * @return ActiveQuery
     */
    public function getRefusalReasons(): ActiveQuery
    {
        return $this->hasMany(RefusalReason::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[TaskResponses]].
     *
     * @return ActiveQuery
     */
    public function getTaskResponses(): ActiveQuery
    {
        return $this->hasMany(TaskResponse::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[CustomerTasks]].
     *
     * @return ActiveQuery
     */
    public function getCustomerTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[PerformerTasks]].
     *
     * @return ActiveQuery
     */
    public function getPerformerTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[UserReviewers]].
     *
     * @return ActiveQuery
     */
    public function getUserReviewers(): ActiveQuery
    {
        return $this->hasMany(UserReview::class, ['reviewer_id' => 'id']);
    }

    /**
     * Gets query for [[UserReviews]].
     *
     * @return ActiveQuery
     */
    public function getUserReviews(): ActiveQuery
    {
        return $this->hasMany(UserReview::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserSettings]].
     *
     * @return ActiveQuery
     */
    public function getUserSettings(): ActiveQuery
    {
        return $this->hasMany(UserSetting::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserSpecializations]].
     *
     * @return ActiveQuery
     */
    public function getUserSpecializations(): ActiveQuery
    {
        return $this->hasMany(UserSpecialization::class, ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // Implement validateAuthKey() method.
    }

    // qstn переменные вынести свойствами?
    public static function getRating(array $reviews, int $userID): null|float|int
    {
        $ratings = [];

        foreach ($reviews as $review) {
            $ratings[] = $review->rating;
        }

        $reviewsCount = count($ratings);
        $reviewsSum = array_sum($ratings);
        $failedTasksCount = (int) RefusalReason::find()->where(['user_id' => $userID])->count();

        if ($reviewsCount || $reviewsSum || $failedTasksCount) {
            return $reviewsSum / ($reviewsCount + $failedTasksCount);
        }

        return null;
    }

    public static function getRank(int $userID): int
    {
        $ratedUsers = User::find()
            ->select(['AVG(ur.rating) AS rating', 'users.id'])
            ->joinWith(['userReviews ur'])
            ->groupBy('users.id')
            ->orderBy('rating DESC')
            ->all();

        return array_search($userID, array_column($ratedUsers, 'id')) + 1;
    }
}
