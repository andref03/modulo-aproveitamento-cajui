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

    <p class="action-toolbar">
        <?php if (($usuario->isAluno() || $usuario->isAdmin()) && $model->podeEditar()): ?>
            <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'action-btn action-btn-edit']) ?>
        <?php endif; ?>

        <?php if ($usuario->isAdmin() || ($usuario->isAluno() && $model->podeEditar())): ?>
            <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
                'class' => 'action-btn action-btn-delete',
                'data' => [
                    'confirm' => 'Tem certeza que deseja excluir esta solicitação?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?php if (($usuario->isCoordenador() || $usuario->isAdmin()) && $model->podeFinalizar()): ?>
        <p>
            <?= Html::beginForm(['finalizar', 'id' => $model->id], 'post') ?>
                <?= Html::submitButton('Finalizar Solicitação', [
                    'class' => 'btn btn-success'
                ]) ?>
            <?= Html::endForm() ?>
        </p>
    <?php endif; ?>

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
                    'template' => '{view} {update} {delete}',
                    'contentOptions' => ['class' => 'text-nowrap'],
                    'buttons' => [
                        'view' => function ($url, $item) {
                            return Html::a('Visualizar', $url, ['class' => 'action-btn action-btn-view']);
                        },
                        'update' => function ($url, $item) use ($usuario, $model) {
                            if (
                                ($usuario->isAluno() && $model->status === 'EM_EDICAO') ||
                                ($usuario->isCoordenador() && $model->status === 'EM_ANALISE') ||
                                $usuario->isAdmin()
                            ) {
                                return Html::a('Editar', $url, ['class' => 'action-btn action-btn-edit']);
                            }
                            return '';
                        },
                        'delete' => function ($url, $item) use ($usuario, $model) {
                            if (($usuario->isAluno() || $usuario->isAdmin()) && $model->status === 'EM_EDICAO') {
                                return Html::a('Excluir', $url, [
                                    'class' => 'action-btn action-btn-delete',
                                    'data' => [
                                        'confirm' => 'Deseja realmente excluir este item?',
                                        'method' => 'post',
                                    ],
                                ]);
                            }
                            return '';
                        },
                    ],
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
