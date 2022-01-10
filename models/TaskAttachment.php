<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "task_attachments".
 *
 * @property int $id
 * @property string $attachment_path
 * @property int $type
 * @property int $task_id
 *
 * @property Task $task
 */
class TaskAttachment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['attachment_path', 'type', 'task_id'], 'required'],
            [['type', 'task_id'], 'integer'],
            [['attachment_path'], 'string', 'max' => 255],
            [['attachment_path'], 'unique'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'attachment_path' => 'Attachment Path',
            'type' => 'Type',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
