<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ItemEquivalencia $model */

$this->title = 'Create Item Equivalencia';
$this->params['breadcrumbs'][] = ['label' => 'Item Equivalencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-equivalencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
