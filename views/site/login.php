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

            <?= $form->errorSummary($model, [
                'class' => 'alert alert-danger',
                'header' => false,
            ]) ?>

            <?= $form->field($model, 'email')->textInput([
                'autofocus' => true,
                'placeholder' => 'Digite seu e-mail',
                'autocomplete' => 'email',
                'inputmode' => 'email'
            ]) ?>

            <?= $form->field($model, 'senha', [
                'template' => "{label}\n<div class=\"input-group\">{input}<button class=\"btn btn-outline-secondary px-3 d-flex align-items-center justify-content-center\" type=\"button\" id=\"toggleSenha\" aria-label=\"Mostrar senha\" title=\"Mostrar senha\"><span id=\"iconEye\" aria-hidden=\"true\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"18\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path d=\"M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z\"/><path d=\"M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5m0 1a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3\"/></svg></span><span id=\"iconEyeSlash\" class=\"d-none\" aria-hidden=\"true\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"18\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path d=\"M13.359 11.238 15.5 13.38l-.707.707-2.12-2.12a8.6 8.6 0 0 1-4.673 1.533C3 13.5 0 8 0 8a16.4 16.4 0 0 1 3.4-4.568L1.146 1.177 1.854.47l12.213 12.213zM11.297 10.176 9.879 8.758A2 2 0 0 1 7.242 6.12L5.824 4.703C4.669 5.274 3.66 6.29 2.873 7.347A13.3 13.3 0 0 0 1.182 8c.058.087.122.183.195.288.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5a7.6 7.6 0 0 0 3.297-.824\"/><path d=\"M10.477 7.355 8.645 5.523a1 1 0 0 1 1.832 1.832M8 3.5c2.12 0 3.879 1.168 5.168 2.457.38.38.72.764 1.014 1.125a.5.5 0 0 0 .778-.628 13.3 13.3 0 0 0-1.086-1.183C12.585 3.98 10.64 2.5 8 2.5a8.6 8.6 0 0 0-2.659.454.5.5 0 1 0 .318.948A7.6 7.6 0 0 1 8 3.5\"/></svg></span></button></div>\n{error}",
            ])->passwordInput([
                'placeholder' => 'Digite sua senha',
                'autocomplete' => 'current-password',
                'id' => 'loginform-senha'
            ]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="d-grid">
                <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary btn-lg']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJs(<<<JS
const senhaInput = document.getElementById('loginform-senha');
const toggleBtn = document.getElementById('toggleSenha');
const iconEye = document.getElementById('iconEye');
const iconEyeSlash = document.getElementById('iconEyeSlash');

if (!senhaInput || !toggleBtn || !iconEye || !iconEyeSlash) return;

toggleBtn.addEventListener('click', function () {
    const mostrando = senhaInput.type === 'text';
    senhaInput.type = mostrando ? 'password' : 'text';
    toggleBtn.setAttribute('aria-label', mostrando ? 'Mostrar senha' : 'Ocultar senha');
    toggleBtn.setAttribute('title', mostrando ? 'Mostrar senha' : 'Ocultar senha');
    iconEye.classList.toggle('d-none', !mostrando);
    iconEyeSlash.classList.toggle('d-none', mostrando);
});
JS);
?>
