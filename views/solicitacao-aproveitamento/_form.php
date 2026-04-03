<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */
/** @var yii\widgets\ActiveForm $form */

$usuario = Yii::$app->user->identity;
?>

<div class="solicitacao-aproveitamento-form">

    <?php if (!$model->isNewRecord): ?>
        <div class="card mb-3 p-3" style="border:1px solid #ddd; border-radius:8px;">
            <h4>Informações da Solicitação</h4>

            <?php if (!empty($model->numero_protocolo)): ?>
                <p><strong>Número de protocolo:</strong>
                    <?= Html::encode($model->numero_protocolo) ?>
                </p>
            <?php endif; ?>

            <p><strong>Status:</strong>
                <?= Html::encode($model->statusFormatado) ?>
            </p>

            <p><strong>Aluno:</strong>
                <?= $model->estudante ? Html::encode($model->estudante->nome) : '-' ?>
            </p>

            <p><strong>Coordenador:</strong>
                <?= $model->coordenador ? Html::encode($model->coordenador->nome) : '-' ?>
            </p>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'errorOptions' => ['class' => 'help-block help-block-error', 'tag' => 'div'],
            'options' => ['class' => 'form-group mb-3'],
        ],
    ]); ?>

    <?php if ($model->isNewRecord): ?>

        <?php if ($usuario && $usuario->isAluno()): ?>
            <div class="alert alert-info">
                <strong>Aluno:</strong> <?= Html::encode($usuario->estudante->nome ?? '-') ?><br>
                <strong>Coordenador responsável:</strong> <?= Html::encode($model->coordenador->nome ?? 'Não definido') ?>
            </div>

            <?= $form->field($model, 'estudante_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'coordenador_id')->hiddenInput()->label(false) ?>

        <?php elseif ($usuario && $usuario->isAdmin()): ?>
            <?= $form->field($model, 'estudante_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Estudante::find()->all(), 'id', 'nome'),
                ['prompt' => 'Selecione o estudante']
            ) ?>

            <?= $form->field($model, 'coordenador_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Coordenador::find()->all(), 'id', 'nome'),
                ['prompt' => 'Selecione o coordenador']
            ) ?>
        <?php endif; ?>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar Solicitação', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>