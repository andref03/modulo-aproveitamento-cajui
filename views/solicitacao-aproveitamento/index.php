<?php

use app\models\SolicitacaoAproveitamento;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamentoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Solicitações';
$this->params['breadcrumbs'][] = $this->title;

$usuario = Yii::$app->user->identity;
?>

<div class="solicitacao-aproveitamento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($usuario->isAluno() || $usuario->isAdmin()): ?>
        <p>
            <?= Html::a('Nova Solicitação', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'numero_protocolo',
                'label' => 'Protocolo',
            ],
            [
                'label' => 'Estudante',
                'value' => function ($model) {
                    return $model->estudante ? $model->estudante->nome : '-';
                }
            ],
            [
                'label' => 'Curso',
                'value' => function ($model) {
                    return $model->estudante && $model->estudante->curso
                        ? $model->estudante->curso->nome
                        : '-';
                }
            ],
            [
                'label' => 'Coordenador',
                'value' => function ($model) {
                    return $model->coordenador ? $model->coordenador->nome : '-';
                }
            ],
            [
                'attribute' => 'status',
                'label' => 'Status',
                'value' => function ($model) {
                    return $model->statusFormatado;
                }
            ],
            [
                'label' => 'Resultado Final',
                'value' => function ($model) {
                    return $model->resultado_final
                        ? $model->resultadoFinalFormatado
                        : '-';
                },
            ],
            [
                'label' => 'Itens',
                'value' => function ($model) {
                    return count($model->itemEquivalencias);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'template' => '{edit} {view} {delete}',
                'contentOptions' => ['class' => 'text-nowrap'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(
                            'Visualizar',
                            ['view', 'id' => $model->id],
                            [
                                'title' => 'Visualizar',
                                'class' => 'action-btn action-btn-view',
                                'aria-label' => 'Visualizar solicitação',
                            ]
                        );
                    },
                    'edit' => function ($url, $model, $key) use ($usuario) {
                        if (($usuario->isAluno() || $usuario->isAdmin()) && $model->podeEditar()) {
                            return Html::a(
                                'Editar',
                                ['update', 'id' => $model->id],
                                [
                                    'title' => 'Editar',
                                    'class' => 'action-btn action-btn-edit',
                                    'aria-label' => 'Editar solicitação',
                                ]
                            );
                        }
                        return '';
                    },
                    'delete' => function ($url, $model, $key) use ($usuario) {
                        if (($usuario->isAluno() || $usuario->isAdmin()) && $model->podeEditar()) {
                            return Html::a(
                                'Excluir',
                                ['delete', 'id' => $model->id],
                                [
                                    'title' => 'Excluir',
                                    'class' => 'action-btn action-btn-delete',
                                    'aria-label' => 'Excluir solicitação',
                                    'data' => [
                                        'confirm' => 'Tem certeza que deseja excluir esta solicitação?',
                                        'method' => 'post',
                                    ],
                                ]
                            );
                        }
                        return '';
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
