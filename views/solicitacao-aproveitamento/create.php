<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */

$this->title = 'Nova Solicitação de Aproveitamento';
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Aproveitamento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="solicitacao-aproveitamento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Preencha os dados iniciais da solicitação. Após salvar, será possível adicionar os itens de equivalência.</p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>