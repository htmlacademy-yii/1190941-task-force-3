<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $status
 * @property string $created_at
 * @property string $title
 * @property string $description
 * @property float|null $lat
 * @property float|null $long
 * @property int|null $price
 * @property string|null $deadline
 * @property int $category_id
 * @property int $customer_id
 * @property int|null $city_id
 * @property int|null $performer_id
 *
 * @property Category $category
 * @property City $city
 * @property User $customer
 * @property User $performer
 * @property RefusalReason[] $refusalReasons
 * @property TaskAttachment[] $taskAttachments
 * @property TaskResponse[] $taskResponses
 * @property UserReview[] $userReviews
 */
class Task extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status', 'price', 'category_id', 'customer_id', 'city_id', 'performer_id'], 'integer'],
            [['created_at', 'deadline'], 'safe'],
            [['title', 'description', 'category_id', 'customer_id'], 'required'],
            [['description'], 'string'],
            [['lat', 'long'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['performer_id' => 'id']],
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
            'created_at' => 'Created At',
            'title' => 'Title',
            'description' => 'Description',
            'lat' => 'Lat',
            'long' => 'Long',
            'price' => 'Price',
            'deadline' => 'Deadline',
            'category_id' => 'Category ID',
            'customer_id' => 'Customer ID',
            'city_id' => 'City ID',
            'performer_id' => 'Performer ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Performer]].
     *
     * @return ActiveQuery
     */
    public function getPerformer(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'performer_id']);
    }

    /**
     * Gets query for [[RefusalReasons]].
     *
     * @return ActiveQuery
     */
    public function getRefusalReasons(): ActiveQuery
    {
        return $this->hasMany(RefusalReason::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskAttachments]].
     *
     * @return ActiveQuery
     */
    public function getTaskAttachments(): ActiveQuery
    {
        return $this->hasMany(TaskAttachment::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskResponses]].
     *
     * @return ActiveQuery
     */
    public function getTaskResponses(): ActiveQuery
    {
        return $this->hasMany(TaskResponse::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[UserReviews]].
     *
     * @return ActiveQuery
     */
    public function getUserReviews(): ActiveQuery
    {
        return $this->hasMany(UserReview::className(), ['task_id' => 'id']);
    }
}
