<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Estudante;
use app\models\Coordenador;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */
/** @var yii\widgets\ActiveForm $form */

$estudantes = ArrayHelper::map(Estudante::find()->orderBy('nome')->all(), 'id', 'nome');
$coordenadores = ArrayHelper::map(Coordenador::find()->orderBy('nome')->all(), 'id', 'nome');
?>

<div class="solicitacao-aproveitamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'estudante_id')->dropDownList(
                $estudantes,
                ['prompt' => 'Selecione o estudante']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'coordenador_id')->dropDownList(
                $coordenadores,
                ['prompt' => 'Selecione o coordenador (opcional)']
            ) ?>
        </div>
    </div>

    <?php if (!$model->isNewRecord): ?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'numero_protocolo')->textInput(['readonly' => true]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'status')->textInput(['readonly' => true]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'resultado_final')->textInput(['readonly' => true]) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Salvar Solicitação', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>