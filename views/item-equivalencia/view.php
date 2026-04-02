<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ItemEquivalencia $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Item Equivalencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="item-equivalencia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'solicitacao_id',
            'disciplina_origem_nome',
            'disciplina_origem_carga_horaria',
            'disciplina_origem_ementa:ntext',
            'instituicao_origem',
            'disciplina_destino_id',
            'parecer',
            'justificativa:ntext',
            'data_analise',
        ],
    ]) ?>

</div>
