<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\DisciplinaIfnmg;
use app\models\SolicitacaoAproveitamento;

/** @var yii\web\View $this */
/** @var app\models\ItemEquivalencia $model */
/** @var yii\widgets\ActiveForm $form */

$disciplinas = ArrayHelper::map(
    DisciplinaIfnmg::find()->orderBy('nome')->all(),
    'id',
    function($disciplina) {
        return $disciplina->nome . ' (' . $disciplina->carga_horaria . 'h)';
    }
);

$solicitacoes = ArrayHelper::map(
    SolicitacaoAproveitamento::find()->all(),
    'id',
    'numero_protocolo'
);
?>

<div class="item-equivalencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card mb-4 p-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Vínculo da Solicitação</h4>

        <?php if ($model->isNewRecord && empty($model->solicitacao_id)): ?>
            <?= $form->field($model, 'solicitacao_id')->dropDownList(
                $solicitacoes,
                ['prompt' => 'Selecione a solicitação']
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
            'placeholder' => 'Ex.: Cálculo I'
        ]) ?>

        <?= $form->field($model, 'disciplina_origem_carga_horaria')->textInput([
            'type' => 'number',
            'min' => 1,
            'placeholder' => 'Ex.: 60'
        ]) ?>

        <?= $form->field($model, 'instituicao_origem')->textInput([
            'maxlength' => true,
            'placeholder' => 'Ex.: Universidade X'
        ]) ?>

        <?= $form->field($model, 'disciplina_origem_ementa')->textarea([
            'rows' => 5,
            'placeholder' => 'Informe a ementa ou conteúdo programático da disciplina cursada anteriormente'
        ]) ?>
    </div>

    <div class="card mb-4 p-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Equivalência pretendida</h4>

        <?= $form->field($model, 'disciplina_destino_id')->dropDownList(
            $disciplinas,
            ['prompt' => 'Selecione a disciplina do IFNMG']
        ) ?>
    </div>

    <div class="card mb-4 p-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4>Análise do coordenador</h4>

        <?= $form->field($model, 'parecer')->dropDownList([
            'PENDENTE' => 'Pendente',
            'DEFERIDO' => 'Deferido',
            'INDEFERIDO' => 'Indeferido',
        ]) ?>

        <?= $form->field($model, 'justificativa')->textarea([
            'rows' => 4,
            'placeholder' => 'Obrigatória apenas em caso de indeferimento'
        ]) ?>

        <?= $form->field($model, 'data_analise')->textInput([
            'type' => 'datetime-local',
            'value' => $model->data_analise ? date('Y-m-d\TH:i', strtotime($model->data_analise)) : ''
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar Item', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Voltar', ['solicitacao-aproveitamento/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>