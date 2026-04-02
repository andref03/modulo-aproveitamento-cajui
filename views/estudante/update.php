<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Estudante $model */

$this->title = 'Editar Estudante: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Estudantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="estudante-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
