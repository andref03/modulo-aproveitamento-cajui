<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UsuarioSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Usuários';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'nome',
            'email:email',
            [
                'attribute' => 'perfil',
                'filter' => [
                    'ADMIN' => 'ADMIN',
                    'ALUNO' => 'ALUNO',
                    'COORDENADOR' => 'COORDENADOR',
                ],
            ],
            [
                'attribute' => 'estudante_id',
                'label' => 'Estudante',
                'value' => function ($model) {
                    return $model->estudante ? $model->estudante->nome : '-';
                },
            ],
            [
                'attribute' => 'coordenador_id',
                'label' => 'Coordenador',
                'value' => function ($model) {
                    return $model->coordenador ? $model->coordenador->nome : '-';
                },
            ],
            [
                'attribute' => 'ativo',
                'value' => function ($model) {
                    return $model->ativo ? 'Ativo' : 'Inativo';
                },
                'filter' => [1 => 'Ativo', 0 => 'Inativo'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>

