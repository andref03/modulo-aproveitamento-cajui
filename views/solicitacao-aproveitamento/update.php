<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */

$this->title = 'Update Solicitacao Aproveitamento: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Solicitacao Aproveitamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="solicitacao-aproveitamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
