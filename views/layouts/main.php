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
    width: 270px;
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
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 25px;
    padding: 0 10px;
    white-space: nowrap;
    overflow: hidden;
}

.sidebar.collapsed .brand-text,
.sidebar.collapsed .menu-text,
.sidebar.collapsed .menu-section-title,
.sidebar.collapsed .submenu {
    display: none;
}

.sidebar a,
.sidebar .menu-parent {
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    transition: background 0.2s ease;
    cursor: pointer;
}

.sidebar a:hover,
.sidebar .menu-parent:hover {
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

.menu-section-title {
    font-size: 12px;
    text-transform: uppercase;
    color: #9ca3af;
    padding: 10px 20px 6px;
    letter-spacing: 0.08em;
}

.submenu {
    background: rgba(255,255,255,0.03);
}

.submenu a {
    padding-left: 52px;
    font-size: 14px;
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
    const aproveitamentoToggle = document.getElementById('aproveitamentoToggle');
    const aproveitamentoMenu = document.getElementById('aproveitamentoMenu');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });
    }

    if (aproveitamentoToggle && aproveitamentoMenu) {
        aproveitamentoToggle.addEventListener('click', function () {
            if (!sidebar.classList.contains('collapsed')) {
                aproveitamentoMenu.style.display =
                    aproveitamentoMenu.style.display === 'none' ? 'block' : 'none';
            }
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
    <title><?= Html::encode($this->title ?: 'Sistema Cajuí') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="layout-wrapper">

    <aside class="sidebar" id="sidebar">
        <button class="toggle-btn" id="sidebarToggle">☰</button>

        <div class="brand">
            <span class="brand-text">Cajuí</span>
        </div>

        <div class="menu-section-title">Navegação</div>

        <a href="<?= \yii\helpers\Url::to(['/site/index']) ?>">
            <span class="menu-icon">🏠</span>
            <span class="menu-text">Início</span>
        </a>

        <div class="menu-section-title">Módulos</div>

        <div class="menu-parent" id="aproveitamentoToggle">
            <span class="menu-icon">📂</span>
            <span class="menu-text">Aproveitamento</span>
        </div>

        <div class="submenu" id="aproveitamentoMenu" style="display:block;">
            <a href="<?= \yii\helpers\Url::to(['/solicitacao-aproveitamento/index']) ?>">Solicitações</a>
            <a href="<?= \yii\helpers\Url::to(['/item-equivalencia/index']) ?>">Itens de Equivalência</a>
            <a href="<?= \yii\helpers\Url::to(['/disciplina-ifnmg/index']) ?>">Disciplinas IFNMG</a>
            <a href="<?= \yii\helpers\Url::to(['/estudante/index']) ?>">Estudantes</a>
            <a href="<?= \yii\helpers\Url::to(['/coordenador/index']) ?>">Coordenadores</a>
            <a href="<?= \yii\helpers\Url::to(['/curso/index']) ?>">Cursos</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            Sistema Cajuí
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