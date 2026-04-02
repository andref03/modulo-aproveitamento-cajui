<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="solicitacao-aproveitamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numero_protocolo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estudante_id')->textInput() ?>

    <?= $form->field($model, 'coordenador_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'resultado_final')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_criacao')->textInput() ?>

    <?= $form->field($model, 'data_envio')->textInput() ?>

    <?= $form->field($model, 'data_finalizacao')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
