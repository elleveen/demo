<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Request $model */

$this->title = 'Создание заяки';

?>
<div class="request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
