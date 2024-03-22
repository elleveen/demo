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
            [['id_category', 'id_user', 'name', 'description', 'photo', 'status', 'description_denied', 'photo_after'], 'required'],
            [['id_category', 'id_user', 'status'], 'integer'],
            [['description', 'description_denied'], 'string'],
            [['datetime'], 'safe'],
            [['name', 'photo', 'photo_after'], 'string', 'max' => 255],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['id_category' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_category' => 'Id Category',
            'id_user' => 'Id User',
            'name' => 'Name',
            'description' => 'Description',
            'photo' => 'Photo',
            'status' => 'Status',
            'datetime' => 'Datetime',
            'description_denied' => 'Description Denied',
            'photo_after' => 'Photo After',
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
}
