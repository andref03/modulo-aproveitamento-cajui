<?php

use yii\helpers\Html;

$this->title = 'Início';
?>

<div class="site-index">
    <div class="p-4 bg-light rounded-3">
        <?php if (!Yii::$app->user->isGuest): ?>
            <h1>Olá, <?= Html::encode(Yii::$app->user->identity->nome) ?>!</h1>
            <p class="lead">Seja bem-vindo ao sistema Cajuí.</p>
        <?php else: ?>
            <h1>Bem-vindo ao sistema Cajuí</h1>
            <p class="lead">Faça login para acessar o sistema.</p>
        <?php endif; ?>
    </div>
</div>