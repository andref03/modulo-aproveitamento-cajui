<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Estudante;
use app\models\Coordenador;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamento $model */
/** @var yii\widgets\ActiveForm $form */

$estudantes = ArrayHelper::map(
    Estudante::find()->all(),
    'id',
    'nome'
);

$coordenadores = ArrayHelper::map(
    Coordenador::find()->all(),
    'id',
    'nome'
);
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
        <?= $form->field($model, 'estudante_id')->dropDownList($estudantes, ['prompt' => 'Selecione o estudante']) ?>
        <?= $form->field($model, 'coordenador_id')->dropDownList($coordenadores, ['prompt' => 'Selecione o coordenador']) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar Solicitação', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>