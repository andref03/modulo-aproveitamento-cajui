<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Curso $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card p-3 mb-4" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Dados do Curso</h4>

        <?= $form->field($model, 'nome')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ex.: Bacharelado em Sistemas de Informação'
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar Curso', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>