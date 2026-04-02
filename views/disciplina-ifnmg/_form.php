<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DisciplinaIfnmg $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="disciplina-ifnmg-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'carga_horaria')->textInput() ?>

    <?= $form->field($model, 'ementa')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'curso_id')->textInput() ?>

    <?= $form->field($model, 'pre_requisito_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
