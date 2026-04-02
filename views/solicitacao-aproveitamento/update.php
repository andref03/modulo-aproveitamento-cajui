<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */

$this->title = 'Editar Solicitação: ' . $model->numero_protocolo;
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Aproveitamento', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->numero_protocolo, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>

<div class="solicitacao-aproveitamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <hr>

    <h3>Itens de Equivalência</h3>

    <p>
        <?= Html::a('Adicionar Item', ['item-equivalencia/create', 'solicitacao_id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php if (count($model->itemEquivalencias) > 0): ?>
        <?= GridView::widget([
            'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->itemEquivalencias,
            'key' => 'id',
            'pagination' => false,
        ]),
            'columns' => [
                'disciplina_origem_nome',
                'disciplina_origem_carga_horaria',
                'instituicao_origem',
                [
                    'attribute' => 'disciplina_destino_id',
                    'label' => 'Disciplina IFNMG',
                    'value' => function ($item) {
                        return $item->disciplinaDestino->nome ?? '-';
                    }
                ],
                'parecer',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'controller' => 'item-equivalencia',
                    'template' => '{view} {update} {delete}',
                ],
            ],
        ]); ?>
    <?php else: ?>
        <div class="alert alert-warning">
            Nenhum item foi adicionado ainda.
        </div>
    <?php endif; ?>

    <div style="margin-top: 20px;">
        <?= Html::beginForm(['enviar', 'id' => $model->id], 'post') ?>
            <?= Html::submitButton('Enviar para Análise', [
                'class' => 'btn btn-warning',
                'disabled' => !$model->podeEnviar(),
            ]) ?>
        <?= Html::endForm() ?>
    </div>

</div>