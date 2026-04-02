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

$this->title = 'Solicitacao Aproveitamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitacao-aproveitamento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Solicitacao Aproveitamento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'numero_protocolo',
            'estudante_id',
            'coordenador_id',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusFormatado;
                },
            ],
            [
                'attribute' => 'resultado_final',
                'label' => 'Resultado Final',
                'value' => function ($model) {
                    return $model->resultadoFinalFormatado;
                },
            ],
            //'resultado_final',
            //'data_criacao',
            //'data_envio',
            //'data_finalizacao',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        if ($model->podeEditar()) {
                            return Html::a(
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
