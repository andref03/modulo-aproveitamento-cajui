<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\DisciplinaIfnmg;
use app\models\SolicitacaoAproveitamento;

$disciplinas = ArrayHelper::map(
    DisciplinaIfnmg::find()->orderBy('nome')->all(),
    'id',
    function($disciplina) {
        return $disciplina->nome . ' (' . $disciplina->carga_horaria . 'h)';
    }
);

$solicitacoes = ArrayHelper::map(
    SolicitacaoAproveitamento::find()->orderBy('id DESC')->all(),
    'id',
    'numero_protocolo'
);

$usuario = Yii::$app->user->identity;

$podeEditarDadosGerais = false;
$podeEditarAnalise = false;

if ($usuario->isAdmin()) {
    $podeEditarDadosGerais = $model->isNewRecord || ($model->solicitacao && $model->solicitacao->status === 'EM_EDICAO');
    $podeEditarAnalise = !$model->isNewRecord && $model->solicitacao && $model->solicitacao->status === 'EM_ANALISE';
} elseif ($usuario->isAluno()) {
    $podeEditarDadosGerais = $model->isNewRecord || ($model->solicitacao && $model->solicitacao->status === 'EM_EDICAO');
    $podeEditarAnalise = false;
} elseif ($usuario->isCoordenador()) {
    $podeEditarDadosGerais = false;
    $podeEditarAnalise = !$model->isNewRecord && $model->solicitacao && $model->solicitacao->status === 'EM_ANALISE';
}
?>

<div class="item-equivalencia-form">

    <?php if (!$model->isNewRecord && $model->solicitacao): ?>
        <?php if ($model->solicitacao->status === 'EM_ANALISE'): ?>
            <div class="alert alert-info">
                Esta solicitação está <strong>em análise</strong>. Apenas o parecer e a justificativa podem ser alterados.
            </div>
        <?php elseif ($model->solicitacao->status === 'FINALIZADA'): ?>
            <div class="alert alert-secondary">
                Esta solicitação está <strong>finalizada</strong>. O item não pode mais ser alterado.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'errorOptions' => [
                'class' => 'text-danger small mt-1',
                'tag' => 'div'
            ],
            'options' => ['class' => 'form-group mb-3'],
        ],
    ]); ?>

    <div class="card mb-4 p-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Vínculo da Solicitação</h4>

        <?php if ($model->isNewRecord && empty($model->solicitacao_id)): ?>
            <?= $form->field($model, 'solicitacao_id')->dropDownList(
                $solicitacoes,
                [
                    'prompt' => 'Selecione a solicitação',
                    'disabled' => !$podeEditarDadosGerais
                ]
            ) ?>
        <?php else: ?>
            <p>
                <strong>Solicitação:</strong>
                <?= $model->solicitacao ? Html::encode($model->solicitacao->numero_protocolo) : $model->solicitacao_id ?>
            </p>
            <?= $form->field($model, 'solicitacao_id')->hiddenInput()->label(false) ?>
        <?php endif; ?>
    </div>

    <div class="card mb-4 p-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Disciplina cursada anteriormente</h4>

        <?= $form->field($model, 'disciplina_origem_nome')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ex.: Cálculo I',
            'readonly' => !$podeEditarDadosGerais
        ]) ?>

        <?= $form->field($model, 'disciplina_origem_carga_horaria')->textInput([
            'type' => 'number',
            'min' => 1,
            'placeholder' => 'Ex.: 60',
            'readonly' => !$podeEditarDadosGerais
        ]) ?>

        <?= $form->field($model, 'instituicao_origem')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ex.: Universidade X',
            'readonly' => !$podeEditarDadosGerais
        ]) ?>

        <?= $form->field($model, 'disciplina_origem_ementa')->textarea([
            'rows' => 5,
            'placeholder' => 'Informe a ementa ou conteúdo programático da disciplina cursada anteriormente',
            'readonly' => !$podeEditarDadosGerais
        ]) ?>
    </div>

    <div class="card mb-4 p-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Equivalência pretendida</h4>

        <?= $form->field($model, 'disciplina_destino_id')->dropDownList(
            $disciplinas,
            [
                'prompt' => 'Selecione a disciplina do IFNMG',
                'disabled' => !$podeEditarDadosGerais
            ]
        ) ?>

        <?php
        // campo disabled não envia valor no POST, então garantimos via hidden input
        if (!$podeEditarDadosGerais && !$model->isNewRecord):
        ?>
            <?= Html::activeHiddenInput($model, 'disciplina_destino_id') ?>
        <?php endif; ?>
    </div>

    <div class="card mb-4 p-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Análise do coordenador</h4>

        <?= $form->field($model, 'parecer')->dropDownList([
            'PENDENTE' => 'Pendente',
            'DEFERIDO' => 'Deferido',
            'INDEFERIDO' => 'Indeferido',
        ], [
            'prompt' => 'Selecione o parecer',
            'disabled' => !$podeEditarAnalise
        ]) ?>

        <?php if (!$podeEditarAnalise && !$model->isNewRecord): ?>
            <?= Html::activeHiddenInput($model, 'parecer') ?>
        <?php endif; ?>

        <?= $form->field($model, 'justificativa')->textarea([
            'rows' => 4,
            'placeholder' => 'Obrigatória apenas em caso de indeferimento',
            'readonly' => !$podeEditarAnalise
        ]) ?>

        <?php if (!$model->isNewRecord && $model->data_analise): ?>
            <p class="text-muted mt-2">
                <strong>Última análise:</strong>
                <?= Yii::$app->formatter->asDatetime($model->data_analise, 'php:d/m/Y H:i:s') ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar Item', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Voltar', ['solicitacao-aproveitamento/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>