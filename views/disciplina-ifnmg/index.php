<?php

use app\models\DisciplinaIfnmg;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\DisciplinaIfnmgSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Disciplina Ifnmgs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disciplina-ifnmg-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Disciplina Ifnmg', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'codigo',
            'nome',
            'carga_horaria',
            'ementa:ntext',
            //'curso_id',
            //'pre_requisito_id',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, DisciplinaIfnmg $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
