<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property int $id_category
 * @property int $id_user
 * @property string $name
 * @property string $description
 * @property string $photo
 * @property int $status
 * @property string $datetime
 * @property string $description_denied
 * @property string $photo_after
 *
 * @property Category $category
 * @property User $user
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $imageFile;
    public static function tableName()
    {
        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_category', 'name', 'description'], 'required'],
            [['id_category', 'id_user', 'status'], 'integer'],
            [['id_user'], 'default', 'value' => Yii::$app->user->identity->getId()],
            [['description', 'description_denied'], 'string'],
            [['description_denied'], 'required', 'on' => 'cancel'],
            [['datetime'], 'safe'],
            [['name', 'photo', 'photo_after'], 'string', 'max' => 255],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['id_category' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => false,
                'extensions' => ['png', 'jpg', 'jpeg', 'bmp'], 'maxSize' => 10*1024*1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_category' => 'Категория',
            'name' => 'Название',
            'description' => 'Описание',
            'photo' => 'Фото до',
            'status' => 'Статус',
            'imageFile' => 'Фото до',
            'datetime' => 'Дата подачи',
            'description_denied' => 'Причина отказа',
            'photo_after' => 'Фото после',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'id_category']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function upload()
    {
        if ($this->validate()){
            $file_name='uploads/' . $this->imageFile->baseName . '.' .$this->imageFile->extension;
            $this->imageFile->saveAs($file_name);
            $this->photo='/'.$file_name;
            return true;
        } else{
            return false;
        }
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['cancel'] = ['description_denied', 'status'];
        $scenarios['success'] = ['imageFile', 'status'];
        return $scenarios;
    }

    public function cancel()
    {
        $this->status = 2;
        if ($this->save()){
            return true;
        }
        return false;
    }

    public function success()
    {
        $this->status = 1;

        if ($this->validate()) {
            $file_name = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            if ($this->imageFile->saveAs($file_name)) {
                $this->photo_after = '/' . $file_name;
                if ($this->save(false)) {
                    return true;
                }
            }
        }
        return false;
    }
}
