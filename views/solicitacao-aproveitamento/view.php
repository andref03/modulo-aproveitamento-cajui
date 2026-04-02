<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Solicitacao Aproveitamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="solicitacao-aproveitamento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'numero_protocolo',
            'estudante_id',
            'coordenador_id',
            'status',
            'resultado_final',
            'data_criacao',
            'data_envio',
            'data_finalizacao',
        ],
    ]) ?>

</div>
