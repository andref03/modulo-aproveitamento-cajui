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

$usuario = Yii::$app->user->identity;
?>

<div class="solicitacao-aproveitamento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->podeSerEditadaPeloUsuario()): ?>
            <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>

        <?php if ($usuario && $usuario->isAdmin()): ?>
            <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Tem certeza que deseja excluir esta solicitação?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>

        <?php if ($model->podeSerEnviadaPeloUsuario()): ?>
            <?= Html::beginForm(['enviar', 'id' => $model->id], 'post', ['style' => 'display:inline-block; margin-left:8px;']) ?>
                <?= Html::submitButton('Enviar para análise', [
                    'class' => 'btn btn-warning',
                    'data' => [
                        'confirm' => 'Após enviar, você não poderá mais editar esta solicitação. Deseja continuar?',
                    ],
                ]) ?>
            <?= Html::endForm() ?>
        <?php endif; ?>
    </p>

    <?php if ($model->podeSerFinalizadaPeloUsuario()): ?>
        <p>
            <?= Html::beginForm(['finalizar', 'id' => $model->id], 'post') ?>
                <?= Html::submitButton('Finalizar Solicitação', [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => 'Tem certeza que deseja finalizar esta solicitação?',
                    ],
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
            [
                'attribute' => 'data_criacao',
                'label' => 'Data de Criação',
                'value' => $model->data_criacao
                    ? Yii::$app->formatter->asDatetime($model->data_criacao, 'php:d/m/Y H:i')
                    : '-',
            ],
            [
                'attribute' => 'data_envio',
                'label' => 'Data de Envio',
                'value' => $model->data_envio
                    ? Yii::$app->formatter->asDatetime($model->data_envio, 'php:d/m/Y H:i')
                    : '-',
            ],
            [
                'attribute' => 'data_finalizacao',
                'label' => 'Data de Finalização',
                'value' => $model->data_finalizacao
                    ? Yii::$app->formatter->asDatetime($model->data_finalizacao, 'php:d/m/Y H:i')
                    : '-',
            ],
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
                    'template' => $model->podeSerEditadaPeloUsuario() ? '{view} {update}' : '{view}',
                ],
            ],
        ]) ?>
    <?php else: ?>
        <div class="alert alert-warning">
            Esta solicitação ainda não possui itens cadastrados.
        </div>
    <?php endif; ?>

    <hr>

    <h3>Histórico de Ações</h3>

    <?php $logs = $model->logAcaos; ?>
    <?php if (count($logs) > 0): ?>
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th style="width: 150px;">Data/Hora</th>
                        <th>Usuário</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= Yii::$app->formatter->asDatetime($log->data_hora, 'php:d/m/Y H:i') ?></td>
                            <td><?= Html::encode($log->usuario_nome) ?></td>
                            <td><?= Html::encode($log->descricao) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Nenhuma ação registrada.
        </div>
    <?php endif; ?>

</div>