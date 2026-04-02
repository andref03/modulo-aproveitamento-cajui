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
            'status',
            //'resultado_final',
            //'data_criacao',
            //'data_envio',
            //'data_finalizacao',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SolicitacaoAproveitamento $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
