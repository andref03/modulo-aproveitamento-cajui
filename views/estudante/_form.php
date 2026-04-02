<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Curso;

/** @var yii\web\View $this */
/** @var app\models\Estudante $model */
/** @var yii\widgets\ActiveForm $form */

$cursos = ArrayHelper::map(
    Curso::find()->orderBy('nome')->all(),
    'id',
    'nome'
);
?>

<div class="estudante-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card p-3 mb-4" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Dados do Estudante</h4>

        <?= $form->field($model, 'nome')->textInput([
            'maxlength' => true,
            'placeholder' => 'Nome completo do estudante'
        ]) ?>

        <?= $form->field($model, 'matricula')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ex.: 176874'
        ]) ?>

        <?= $form->field($model, 'email')->textInput([
            'maxlength' => true,
            'placeholder' => 'email@aluno.ifnmg.edu.br'
        ]) ?>

        <?= $form->field($model, 'curso_id')->dropDownList(
            $cursos,
            ['prompt' => 'Selecione o curso']
        ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar Estudante', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>