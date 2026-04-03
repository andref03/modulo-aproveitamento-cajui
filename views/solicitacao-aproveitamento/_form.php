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

    <?php if ($usuario && ($usuario->isAluno() || $usuario->isAdmin())): ?>
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
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        if ($model->podeSerEditadaPeloUsuario()) {
                            return Html::a(
                                'Editar',
                                ['update', 'id' => $model->id],
                                ['class' => 'btn btn-sm btn-primary me-1']
                            );
                        }
                        return '';
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a(
                            'Ver',
                            ['view', 'id' => $model->id],
                            ['class' => 'btn btn-sm btn-outline-secondary me-1']
                        );
                    },
                    'delete' => function ($url, $model, $key) use ($usuario) {
                        if ($usuario && $usuario->isAdmin()) {
                            return Html::a(
                                'Excluir',
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-sm btn-danger',
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