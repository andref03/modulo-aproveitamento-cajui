<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Alert;

AppAsset::register($this);

$this->registerCss("
body {
    background-color: #f5f7fb;
    margin: 0;
    font-family: Arial, sans-serif;
}

.layout-wrapper {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 260px;
    background: #1f2937;
    color: #fff;
    transition: all 0.3s ease;
    padding-top: 20px;
    position: relative;
}

.sidebar.collapsed {
    width: 80px;
}

.sidebar .brand {
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 25px;
    padding: 0 10px;
    white-space: nowrap;
    overflow: hidden;
}

.sidebar.collapsed .brand-text,
.sidebar.collapsed .menu-text {
    display: none;
}

.sidebar a {
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    transition: background 0.2s ease;
}

.sidebar a:hover {
    background: #374151;
}

.sidebar .menu-icon {
    width: 24px;
    text-align: center;
    margin-right: 12px;
    font-size: 16px;
}

.sidebar.collapsed .menu-icon {
    margin-right: 0;
}

.toggle-btn {
    position: absolute;
    top: 15px;
    right: -15px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    font-weight: bold;
    box-shadow: 0 2px 6px rgba(0,0,0,.2);
}

.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.topbar {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 16px 24px;
    font-size: 22px;
    font-weight: bold;
    color: #111827;
}

.page-content {
    padding: 24px;
}

.card-page {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
");

$this->registerJs("
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });
    }
});
");
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title ?: 'Módulo de Aproveitamento - Cajui') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="layout-wrapper">

    <aside class="sidebar" id="sidebar">
        <button class="toggle-btn" id="sidebarToggle">☰</button>

        <div class="brand">
            <span class="brand-text">Cajui</span>
        </div>

        <a href="<?= \yii\helpers\Url::to(['/site/index']) ?>">
            <span class="menu-icon">🏠</span>
            <span class="menu-text">Início</span>
        </a>

        <a href="<?= \yii\helpers\Url::to(['/solicitacao-aproveitamento/index']) ?>">
            <span class="menu-icon">📄</span>
            <span class="menu-text">Solicitações</span>
        </a>

        <a href="<?= \yii\helpers\Url::to(['/item-equivalencia/index']) ?>">
            <span class="menu-icon">📚</span>
            <span class="menu-text">Itens de Equivalência</span>
        </a>

        <a href="<?= \yii\helpers\Url::to(['/estudante/index']) ?>">
            <span class="menu-icon">🎓</span>
            <span class="menu-text">Estudantes</span>
        </a>

        <a href="<?= \yii\helpers\Url::to(['/coordenador/index']) ?>">
            <span class="menu-icon">👨‍🏫</span>
            <span class="menu-text">Coordenadores</span>
        </a>

        <a href="<?= \yii\helpers\Url::to(['/curso/index']) ?>">
            <span class="menu-icon">🏫</span>
            <span class="menu-text">Cursos</span>
        </a>

        <a href="<?= \yii\helpers\Url::to(['/disciplina-ifnmg/index']) ?>">
            <span class="menu-icon">📘</span>
            <span class="menu-text">Disciplinas IFNMG</span>
        </a>

        <a href="<?= \yii\helpers\Url::to(['/log-acao/index']) ?>">
            <span class="menu-icon">🕒</span>
            <span class="menu-text">Logs</span>
        </a>
    </aside>

    <main class="main-content">
        <div class="topbar">
            Módulo de Aproveitamento de Estudos
        </div>

        <div class="page-content">
            <div class="card-page">

                <?php if (!empty($this->params['breadcrumbs'])): ?>
                    <?= Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'],
                    ]) ?>
                <?php endif ?>

                <?= Alert::widget() ?>

                <?= $content ?>
            </div>
        </div>
    </main>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>