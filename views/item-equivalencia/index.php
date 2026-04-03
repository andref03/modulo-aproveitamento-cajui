<?php

use app\models\ItemEquivalencia;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\ItemEquivalenciaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Itens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-equivalencia-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $usuario = Yii::$app->user->identity; ?>

    <?php if ($usuario->isAluno() || $usuario->isAdmin()): ?>
        <p>
            <?= Html::a('Novo Item', ['create', 'solicitacao_id' => Yii::$app->request->get('id')], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            [
                'label' => 'Protocolo da Solicitação',
                'value' => function ($model) {
                    return $model->solicitacao ? $model->solicitacao->numero_protocolo : '-';
                }
            ],

            [
                'attribute' => 'disciplina_origem_nome',
                'label' => 'Disciplina de Origem',
            ],

            [
                'attribute' => 'disciplina_origem_carga_horaria',
                'label' => 'CH Origem',
            ],

            [
                'label' => 'Disciplina IFNMG',
                'value' => function ($model) {
                    return $model->disciplinaDestino ? $model->disciplinaDestino->nome : '-';
                }
            ],

            [
                'attribute' => 'instituicao_origem',
                'label' => 'Instituição de Origem',
            ],

            [
                'attribute' => 'parecer',
                'label' => 'Parecer',
                'format' => 'raw',
                'value' => function ($model) {
                    return match ($model->parecer) {
                        'PENDENTE' => '<span class="badge bg-warning text-dark">Pendente</span>',
                        'DEFERIDO' => '<span class="badge bg-success">Deferido</span>',
                        'INDEFERIDO' => '<span class="badge bg-danger">Indeferido</span>',
                        default => Html::encode($model->parecer),
                    };
                }
            ],

            [
                'attribute' => 'data_analise',
                'label' => 'Data da Análise',
                'value' => function ($model) {
                    return $model->data_analise
                        ? Yii::$app->formatter->asDatetime($model->data_analise, 'php:d/m/Y H:i')
                        : '-';
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
