<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
use app\models\Request;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<h1>Административная панель</h1>

<p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'category.name',
            'user.fullname',
            'name',
            'description:ntext',
            [
                'label' => 'Статус заявки',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($data){
                    if ($data->status==0){
                        return 'Новая ' .Html::a('Отменить', "/request/cancel?id=$data->id")
                            . ' ' .Html::a('Решить', "/request/success?id=$data->id");
                    };
                    if ($data->status==1) return 'Завершена';
                    if ($data->status==2) return 'Отменена';
                },
                'filter' => ['0' => 'Новая', '1' => 'Решена', '2' => 'Отклонена'],
                'filterInputOptions' => ['prompt' => 'Все статусы', 'class' => 'form-control', 'id' => 'null'],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</p>

<p>
    <?= Html::a('Управление категорями', ['category/index'], ['class' => 'btn btn-success']) ?>
</p>
