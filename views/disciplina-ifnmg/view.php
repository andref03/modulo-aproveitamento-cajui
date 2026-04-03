<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\DisciplinaIfnmg $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Disciplinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$usuario = Yii::$app->user->identity;
$isAdmin = $usuario && $usuario->isAdmin();
?>
<div class="disciplina-ifnmg-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($isAdmin): ?>
        <p>
            <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Você tem certeza que deseja excluir esta disciplina?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'codigo',
            'nome',
            'carga_horaria',
            'ementa:ntext',
            'curso_id',
            'pre_requisito_id',
        ],
    ]) ?>

</div>
