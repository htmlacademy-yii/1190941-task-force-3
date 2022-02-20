<?php

namespace app\models;

use Throwable;
use Yii;
use yii\base\Model;

class TaskAddForm extends Model
{
    public $id;
    public $title;
    public $description;
    public $lat;
    public $long;
    public $price;
    public $deadline;
    public $category_id;
    public $city_id;
    public $files;

    public function attributeLabels(): array
    {
        return [
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'price' => 'Бюджет',
            'deadline' => 'Срок исполнения',
            'category_id' => 'Категория',
            'city_id' => 'Локация',
            'files' => 'Файлы',
        ];
    }

    public function rules(): array
    {
        return [
            [['category_id', 'city_id'], 'integer'],
            [['created_at', 'deadline'], 'safe'],
            [['title', 'description', 'category_id'], 'required'],
            ['price', 'integer', 'min' => 1],
            [['description'], 'string', 'min' => 30],
            [['deadline'], 'date', 'format' => 'yyyy-mm-dd', 'min' => Task::getCurrentDate()],
            [['lat', 'long'], 'number'],
            [['title', 'description'], 'trim'],
            [['title', 'description'], 'filter', 'filter' => function ($value) {
                return preg_replace('/[\s]{2,}/i', ' ', $value);
            }],
            [['title', 'description'], 'checkLength'],
            [['title'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => 'id'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => 'id'],
            [['files'], 'file', 'extensions' => 'png, jpg, svg, pdf, docx, docm, doc, xlsx, xlsm, xlsb, xls', 'maxFiles' => 10],
        ];
    }

    public function checkLength($attribute)
    {
        if ($attribute === 'title' && mb_strlen(preg_replace('/[\s]/i', '', $this->title)) < 10) {
            $this->addError('title', 'Длина должна быть не меньше 10 не пробельных символов');
        }

        if ($attribute === 'description' && mb_strlen(preg_replace('/[\s]/i', '', $this->description)) < 30) {
            $this->addError('description', 'Длина должна быть не меньше 30 не пробельных символов');
        }
    }

    public function save()
    {
        $newTask = new Task();
        $newTask->title = $this->title;
        $newTask->description = $this->description;
        $newTask->lat = $this->lat;
        $newTask->long = $this->long;
        $newTask->price = $this->price;
        $newTask->deadline = $this->deadline;
        $newTask->category_id = $this->category_id;
        $newTask->city_id = $this->city_id;
        $newTask->save();

        $this->id = $newTask->id;
        $this->saveAttachments($this->id);
    }

    private function saveAttachments($taskID)
    {
        $filesData = $this->getFilesData();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($filesData as $fileData) {
                $this->saveAttachment($fileData, $taskID);
            }

            $transaction->commit();

        } catch(Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    private function getFilesData(): array
    {
        $filesData = [];

        if ($this->files && $this->validate()) {
            $i = 0;

            foreach ($this->files as $file) {
                $newFileName = uniqid('upload') . '.' . $file->getExtension();
                $file->saveAs('@webroot/uploads/' . $newFileName);
                $filesData[$i]['path'] = $newFileName;
                $filesData[$i]['size'] = $file->size;
                $i++;
            }
        }

        return $filesData;
    }

    private function saveAttachment(array $fileData, $taskID)
    {
        $newAttachment = new TaskAttachment();
        $newAttachment->attachment_path = $fileData['path'];
        $newAttachment->size = $fileData['size'];
        $newAttachment->task_id = $taskID;
        $newAttachment->save(false);
    }
}
