<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "user_settings".
 *
 * @property int $id
 * @property int|null $contacts_to_customer_only
 * @property int $user_id
 *
 * @property User $user
 */
class UserSetting extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['contacts_to_customer_only', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'contacts_to_customer_only' => 'Contacts To Customer Only',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
