<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

$this->title = 'Login';
?>

<div class="site-login d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 420px; border-radius: 16px;">
        <h2 class="text-center mb-3">Sistema Cajui</h2>
        <p class="text-center text-muted mb-4">Faça login para acessar o sistema</p>

        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'email')->textInput([
                'autofocus' => true,
                'placeholder' => 'Digite seu e-mail'
            ]) ?>

            <?= $form->field($model, 'senha')->passwordInput([
                'placeholder' => 'Digite sua senha'
            ]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="d-grid">
                <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary btn-lg']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>