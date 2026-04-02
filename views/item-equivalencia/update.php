<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ItemEquivalencia $model */

$this->title = 'Update Item Equivalencia: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Item Equivalencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-equivalencia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
