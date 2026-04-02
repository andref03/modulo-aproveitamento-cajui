<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Curso;
use app\models\DisciplinaIfnmg;

/** @var yii\web\View $this */
/** @var app\models\DisciplinaIfnmg $model */
/** @var yii\widgets\ActiveForm $form */

$cursos = ArrayHelper::map(
    Curso::find()->orderBy('nome')->all(),
    'id',
    'nome'
);

$preRequisitos = ArrayHelper::map(
    DisciplinaIfnmg::find()->orderBy('nome')->all(),
    'id',
    function ($disciplina) {
        return $disciplina->codigo . ' - ' . $disciplina->nome;
    }
);
?>

<div class="disciplina-ifnmg-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card p-3 mb-4" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Dados da Disciplina</h4>

        <?= $form->field($model, 'codigo')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ex.: MAT101'
        ]) ?>

        <?= $form->field($model, 'nome')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ex.: Cálculo I'
        ]) ?>

        <?= $form->field($model, 'carga_horaria')->textInput([
            'type' => 'number',
            'min' => 1,
            'placeholder' => 'Ex.: 60'
        ]) ?>

        <?= $form->field($model, 'ementa')->textarea([
            'rows' => 5,
            'placeholder' => 'Descreva a ementa da disciplina'
        ]) ?>

        <?= $form->field($model, 'curso_id')->dropDownList(
            $cursos,
            ['prompt' => 'Selecione o curso']
        ) ?>

        <?= $form->field($model, 'pre_requisito_id')->dropDownList(
            $preRequisitos,
            ['prompt' => 'Sem pré-requisito']
        ) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar Disciplina', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>