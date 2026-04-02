<?php

use app\models\Coordenador;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\CoordenadorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Coordenadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coordenador-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Novo Coordenador', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            [
                'attribute' => 'nome',
                'label' => 'Nome do Coordenador',
            ],

            [
                'attribute' => 'email',
                'label' => 'E-mail',
                'format' => 'email',
            ],

            [
                'label' => 'Curso Coordenado',
                'value' => function ($model) {
                    return $model->curso ? $model->curso->nome : '-';
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
