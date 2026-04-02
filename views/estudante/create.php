<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Estudante $model */

$this->title = 'Novo Estudante';
$this->params['breadcrumbs'][] = ['label' => 'Estudantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estudante-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
