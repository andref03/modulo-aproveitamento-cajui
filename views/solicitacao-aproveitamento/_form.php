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

    <div class="card mb-3 p-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Informações da Solicitação</h4>

        <p><strong>Número de protocolo:</strong>
            <?= $model->numero_protocolo ? Html::encode($model->numero_protocolo) : '<em>Será gerado automaticamente</em>' ?>
        </p>

        <p><strong>Status:</strong>
            <?= $model->status ? str_replace('_', ' ', $model->status) : 'EM EDIÇÃO' ?>
        </p>

        <?php if (!$model->isNewRecord): ?>
            <p><strong>Aluno:</strong>
                <?= $model->estudante ? Html::encode($model->estudante->nome) : '-' ?>
            </p>

            <p><strong>Coordenador:</strong>
                <?= $model->coordenador ? Html::encode($model->coordenador->nome) : '-' ?>
            </p>
        <?php endif; ?>
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'estudante_id')->dropDownList($estudantes, ['prompt' => 'Selecione o estudante']) ?>

    <?= $form->field($model, 'coordenador_id')->dropDownList($coordenadores, ['prompt' => 'Selecione o coordenador']) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar Solicitação', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>