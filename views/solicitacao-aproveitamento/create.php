<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */

$this->title = 'Create Solicitacao Aproveitamento';
$this->params['breadcrumbs'][] = ['label' => 'Solicitacao Aproveitamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-aproveitamento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
