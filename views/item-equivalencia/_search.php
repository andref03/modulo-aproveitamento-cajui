<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ItemEquivalenciaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="item-equivalencia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'solicitacao_id') ?>

    <?= $form->field($model, 'disciplina_origem_nome') ?>

    <?= $form->field($model, 'disciplina_origem_carga_horaria') ?>

    <?= $form->field($model, 'disciplina_origem_ementa') ?>

    <?php // echo $form->field($model, 'instituicao_origem') ?>

    <?php // echo $form->field($model, 'disciplina_destino_id') ?>

    <?php // echo $form->field($model, 'parecer') ?>

    <?php // echo $form->field($model, 'justificativa') ?>

    <?php // echo $form->field($model, 'data_analise') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
