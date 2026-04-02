<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Curso;

/** @var yii\web\View $this */
/** @var app\models\Coordenador $model */
/** @var yii\widgets\ActiveForm $form */

$cursos = ArrayHelper::map(
    Curso::find()->orderBy('nome')->all(),
    'id',
    'nome'
);
?>

<div class="coordenador-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card p-3 mb-4" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Dados do Coordenador</h4>

        <?= $form->field($model, 'nome')->textInput([
            'maxlength' => true,
            'placeholder' => 'Nome completo do coordenador'
        ]) ?>

        <?= $form->field($model, 'email')->textInput([
            'maxlength' => true,
            'placeholder' => 'email@ifnmg.edu.br'
        ]) ?>

        <?= $form->field($model, 'curso_id')->dropDownList(
            $cursos,
            ['prompt' => 'Selecione o curso']
        ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar Coordenador', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>