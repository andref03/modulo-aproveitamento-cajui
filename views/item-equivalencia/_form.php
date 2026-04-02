<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\DisciplinaIfnmg;

/** @var yii\web\View $this */
/** @var app\models\ItemEquivalencia $model */
/** @var yii\widgets\ActiveForm $form */

$disciplinas = ArrayHelper::map(
    DisciplinaIfnmg::find()->orderBy('nome')->all(),
    'id',
    function ($disciplina) {
        return $disciplina->nome . ' (' . $disciplina->codigo . ')';
    }
);
?>

<div class="item-equivalencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'solicitacao_id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'disciplina_origem_nome')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'disciplina_origem_carga_horaria')->textInput() ?>
        </div>
    </div>

    <?= $form->field($model, 'instituicao_origem')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disciplina_origem_ementa')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'disciplina_destino_id')->dropDownList(
        $disciplinas,
        ['prompt' => 'Selecione a disciplina do IFNMG']
    ) ?>

    <?php if (!$model->isNewRecord): ?>
        <hr>
        <h4>Análise do Coordenador</h4>

        <?= $form->field($model, 'parecer')->dropDownList([
            'PENDENTE' => 'PENDENTE',
            'DEFERIDO' => 'DEFERIDO',
            'INDEFERIDO' => 'INDEFERIDO',
        ]) ?>

        <?= $form->field($model, 'justificativa')->textarea(['rows' => 4]) ?>
    <?php endif; ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Salvar Item', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>