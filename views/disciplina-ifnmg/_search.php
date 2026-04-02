<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DisciplinaIfnmgSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="disciplina-ifnmg-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'carga_horaria') ?>

    <?= $form->field($model, 'ementa') ?>

    <?php // echo $form->field($model, 'curso_id') ?>

    <?php // echo $form->field($model, 'pre_requisito_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
