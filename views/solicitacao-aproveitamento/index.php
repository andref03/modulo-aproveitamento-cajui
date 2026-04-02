<?php

use app\models\SolicitacaoAproveitamento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamentoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Solicitações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-aproveitamento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nova Solicitação', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                'label' => 'Itens',
                'value' => function ($model) {
                    return count($model->itemEquivalencias);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        if ($model->podeEditar()) {
                            return \yii\helpers\Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                $url,
                                ['title' => 'Editar']
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
