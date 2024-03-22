<?php

use app\models\Request;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>

<p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'datetime',
                'format' => ['date', 'php:Y-m-d']
            ],
            'category.name',
            'user.fullname',
            'name',
            'description:ntext',
            'status',
            [
                'label' => 'Фото заявки',
                'format' => 'raw',
                'value' => function ($data){
                    return Html::img($data->photo, ['style' => 'width: 250px', 'alt' => 'image']);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</p>

<p>
    <?= Html::a('Создать заявку', ['create'], ['class' => 'btn btn-success']) ?>
</p>