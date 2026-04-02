<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SolicitacaoAproveitamentoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="solicitacao-aproveitamento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'numero_protocolo') ?>

    <?= $form->field($model, 'estudante_id') ?>

    <?= $form->field($model, 'coordenador_id') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'resultado_final') ?>

    <?php // echo $form->field($model, 'data_criacao') ?>

    <?php // echo $form->field($model, 'data_envio') ?>

    <?php // echo $form->field($model, 'data_finalizacao') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
