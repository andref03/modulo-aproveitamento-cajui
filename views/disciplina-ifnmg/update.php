<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DisciplinaIfnmg $model */

$this->title = 'Update Disciplina Ifnmg: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Disciplina Ifnmgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="disciplina-ifnmg-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
