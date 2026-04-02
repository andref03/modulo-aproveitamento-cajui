<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ItemEquivalencia $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="item-equivalencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'solicitacao_id')->textInput() ?>

    <?= $form->field($model, 'disciplina_origem_nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disciplina_origem_carga_horaria')->textInput() ?>

    <?= $form->field($model, 'disciplina_origem_ementa')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'instituicao_origem')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disciplina_destino_id')->textInput() ?>

    <?= $form->field($model, 'parecer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'justificativa')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'data_analise')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
