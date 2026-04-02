<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */

$this->title = 'Solicitação ' . $model->numero_protocolo;
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Aproveitamento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>

<div class="solicitacao-aproveitamento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->podeEditar()): ?>
            <?= Html::a('Editar Solicitação', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>

        <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem certeza que deseja excluir esta solicitação?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php if ($model->podeFinalizar()): ?>
        <p>
            <?= Html::beginForm(['finalizar', 'id' => $model->id], 'post') ?>
                <?= Html::submitButton('Finalizar Solicitação', [
                    'class' => 'btn btn-success'
                ]) ?>
            <?= Html::endForm() ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'numero_protocolo',
            [
                'attribute' => 'estudante_id',
                'label' => 'Estudante',
                'value' => $model->estudante->nome ?? '-',
            ],
            [
                'attribute' => 'coordenador_id',
                'label' => 'Coordenador',
                'value' => $model->coordenador->nome ?? '-',
            ],
            [
                'attribute' => 'status',
                'value' => $model->statusFormatado,
            ],
            [
                'attribute' => 'resultado_final',
                'label' => 'Resultado Final',
                'value' => $model->resultadoFinalFormatado,
            ],
            'data_criacao',
            'data_envio',
            'data_finalizacao',
        ],
    ]) ?>

    <hr>

    <h3>Itens da Solicitação</h3>

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
                    'template' => '{view} {update}',
                ],
            ],
        ]) ?>
    <?php else: ?>
        <div class="alert alert-warning">
            Esta solicitação ainda não possui itens cadastrados.
        </div>
    <?php endif; ?>

</div>