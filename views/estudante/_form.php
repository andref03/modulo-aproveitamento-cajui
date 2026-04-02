<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Estudante $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="estudante-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'matricula')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php
    use yii\helpers\ArrayHelper;
    use app\models\Curso;

    $cursos = ArrayHelper::map(Curso::find()->orderBy('nome')->all(), 'id', 'nome');
    ?>

    <?= $form->field($model, 'curso_id')->dropDownList($cursos, ['prompt' => 'Selecione o curso']) ?>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
