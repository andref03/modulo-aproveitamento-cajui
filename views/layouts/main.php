<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\Url;

AppAsset::register($this);

$usuario = Yii::$app->user->identity ?? null;
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$route = $controller . '/' . $action;

function isActive($routes = [])
{
    $current = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
    foreach ((array)$routes as $r) {
        if ($current === $r) {
            return 'active';
        }
    }
    return '';
}

$this->registerCss("
body {
    background: #f3f6fb;
    margin: 0;
    font-family: 'Inter', Arial, sans-serif;
    color: #111827;
}

.layout-wrapper {
    display: flex;
    min-height: 100vh;
}

/* =========================
   SIDEBAR
========================= */
.sidebar {
    width: 270px;
    background: linear-gradient(180deg, #111827 0%, #1f2937 100%);
    color: #fff;
    transition: width 0.28s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 20px rgba(0,0,0,0.08);
    z-index: 10;
}

.sidebar.collapsed {
    width: 84px;
}

.sidebar-header {
    padding: 22px 20px 18px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 78px;
}

.brand-logo {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 18px;
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.28);
    flex-shrink: 0;
}

.brand-meta {
    overflow: hidden;
}

.brand-title {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.1;
    white-space: nowrap;
}

.brand-subtitle {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
    white-space: nowrap;
}

.sidebar.collapsed .brand-meta,
.sidebar.collapsed .menu-text,
.sidebar.collapsed .menu-section-title,
.sidebar.collapsed .submenu,
.sidebar.collapsed .sidebar-user-info,
.sidebar.collapsed .menu-arrow {
    display: none !important;
}

.sidebar-menu {
    flex: 1;
    padding: 14px 10px 12px;
    overflow-y: auto;
}

.menu-section-title {
    font-size: 11px;
    text-transform: uppercase;
    color: #9ca3af;
    padding: 16px 14px 8px;
    letter-spacing: 0.08em;
    font-weight: 700;
}

.sidebar a.menu-link,
.sidebar .menu-parent {
    color: #e5e7eb;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    margin: 4px 0;
    border-radius: 12px;
    transition: all 0.2s ease;
    cursor: pointer;
    font-weight: 500;
    position: relative;
}

.sidebar a.menu-link:hover,
.sidebar .menu-parent:hover {
    background: rgba(255,255,255,0.08);
    color: #fff;
    transform: translateX(2px);
}

.sidebar a.menu-link.active,
.sidebar .menu-parent.active {
    background: linear-gradient(90deg, rgba(59,130,246,0.20), rgba(37,99,235,0.20));
    color: #fff;
    box-shadow: inset 0 0 0 1px rgba(59,130,246,0.25);
}

.menu-icon {
    width: 22px;
    min-width: 22px;
    text-align: center;
    font-size: 17px;
}

.menu-text {
    flex: 1;
    white-space: nowrap;
}

.menu-arrow {
    font-size: 12px;
    color: #9ca3af;
    transition: transform 0.2s ease;
}

.menu-parent.open .menu-arrow {
    transform: rotate(180deg);
}

.submenu {
    display: none;
    padding-left: 12px;
    margin-top: 4px;
}

.submenu.show {
    display: block;
}

.submenu a {
    color: #cbd5e1;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px 10px 38px;
    margin: 3px 0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.submenu a:hover {
    background: rgba(255,255,255,0.06);
    color: #fff;
}

.submenu a.active {
    background: rgba(59,130,246,0.18);
    color: #fff;
}

/* =========================
   SIDEBAR FOOTER
========================= */
.sidebar-footer {
    border-top: 1px solid rgba(255,255,255,0.08);
    padding: 16px 14px;
}

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.sidebar-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #374151;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}

.sidebar-user-info {
    overflow: hidden;
}

.sidebar-user-name {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-user-role {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 2px;
}

.logout-btn {
    width: 100%;
    border: none;
    background: rgba(239,68,68,0.12);
    color: #fecaca;
    border-radius: 12px;
    padding: 10px 14px;
    text-align: left;
    font-weight: 600;
    transition: all 0.2s ease;
}

.logout-btn:hover {
    background: rgba(239,68,68,0.20);
    color: #fff;
}

/* =========================
   TOGGLE
========================= */
.toggle-btn {
    position: absolute;
    top: 22px;
    right: -14px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    font-size: 15px;
    box-shadow: 0 8px 18px rgba(37,99,235,0.28);
    transition: transform 0.2s ease;
}

.toggle-btn:hover {
    transform: scale(1.05);
}

/* =========================
   MAIN
========================= */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.topbar {
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(8px);
    border-bottom: 1px solid #e5e7eb;
    padding: 18px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.topbar-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
}

.topbar-user {
    font-size: 14px;
    color: #6b7280;
}

.page-content {
    padding: 28px;
}

.card-page {
    background: white;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 10px 30px rgba(15,23,42,0.06);
}

.action-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 30px;
    padding: 5px 12px;
    border-radius: 999px;
    border: 1px solid transparent;
    font-size: 12px;
    font-weight: 600;
    line-height: 1.2;
    text-decoration: none;
    transition: all 0.18s ease;
}

.action-btn:hover,
.action-btn:focus {
    text-decoration: none;
    transform: translateY(-1px);
}

.action-btn:focus-visible {
    outline: 2px solid #1d4ed8;
    outline-offset: 2px;
}

.action-btn-view {
    background: #eff6ff;
    color: #1e40af;
    border-color: #bfdbfe;
}

.action-btn-view:hover,
.action-btn-view:focus {
    background: #dbeafe;
    color: #1e3a8a;
}

.action-btn-edit {
    background: #ecfdf5;
    color: #065f46;
    border-color: #a7f3d0;
}

.action-btn-edit:hover,
.action-btn-edit:focus {
    background: #d1fae5;
    color: #064e3b;
}

.action-btn-delete {
    background: #fef2f2;
    color: #b91c1c;
    border-color: #fecaca;
}

.action-btn-delete:hover,
.action-btn-delete:focus {
    background: #fee2e2;
    color: #991b1b;
}

/* =========================
   RESPONSIVO
========================= */
@media (max-width: 991px) {
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 999;
    }

    .main-content {
        margin-left: 270px;
    }

    .sidebar.collapsed + .main-content {
        margin-left: 84px;
    }
}

@media (max-width: 768px) {
    .topbar {
        padding: 16px 18px;
    }

    .page-content {
        padding: 18px;
    }

    .card-page {
        padding: 20px;
    }
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
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title ?: 'Sistema Cajuí') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="layout-wrapper">

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-header">
            <div class="brand-logo">C</div>
            <div class="brand-meta">
                <div class="brand-title">Sistema Cajuí</div>
            </div>
        </div>

        <div class="sidebar-menu">

            <div class="menu-section-title">Navegação</div>

            <a href="<?= Url::to(['/site/index']) ?>" class="menu-link <?= isActive(['site/index']) ?>">
                <span class="menu-icon">🏠</span>
                <span class="menu-text">Início</span>
            </a>

            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="menu-section-title">Módulos</div>

                <a href="<?= Url::to(['/solicitacao-aproveitamento/index']) ?>" class="menu-link <?= isActive(['solicitacao-aproveitamento/index']) ?>">
                    <span class="menu-icon">📄</span>
                    <span class="menu-text">Solicitações</span>
                </a>

                <?php if ($usuario && $usuario->isAdmin()): ?>
                    <div class="menu-section-title">Administração</div>

                    <a href="<?= Url::to(['/usuario/index']) ?>" class="menu-link <?= isActive(['usuario/index']) ?>">
                        <span class="menu-icon">👤</span>
                        <span class="menu-text">Usuários</span>
                    </a>

                    <a href="<?= Url::to(['/disciplina-ifnmg/index']) ?>" class="menu-link <?= isActive(['disciplina-ifnmg/index']) ?>">
                        <span class="menu-icon">📚</span>
                        <span class="menu-text">Disciplinas IFNMG</span>
                    </a>

                    <a href="<?= Url::to(['/curso/index']) ?>" class="menu-link <?= isActive(['curso/index']) ?>">
                        <span class="menu-icon">🏫</span>
                        <span class="menu-text">Cursos</span>
                    </a>

                    <a href="<?= Url::to(['/coordenador/index']) ?>" class="menu-link <?= isActive(['coordenador/index']) ?>">
                        <span class="menu-icon">🧑‍🏫</span>
                        <span class="menu-text">Coordenadores</span>
                    </a>

                    <a href="<?= Url::to(['/estudante/index']) ?>" class="menu-link <?= isActive(['estudante/index']) ?>">
                        <span class="menu-icon">🎓</span>
                        <span class="menu-text">Estudantes</span>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (Yii::$app->user->isGuest): ?>
                <div class="menu-section-title">Acesso</div>

                <a href="<?= Url::to(['/site/login']) ?>" class="menu-link <?= isActive(['site/login']) ?>">
                    <span class="menu-icon">🔐</span>
                    <span class="menu-text">Login</span>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">
                        <?= strtoupper(mb_substr($usuario->nome, 0, 1)) ?>
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?= Html::encode($usuario->nome) ?></div>
                        <div class="sidebar-user-role"><?= Html::encode($usuario->perfil) ?></div>
                    </div>
                </div>

                <?= Html::beginForm(['/site/logout'], 'post') ?>
                    <?= Html::submitButton('🚪 Sair', ['class' => 'logout-btn']) ?>
                <?= Html::endForm() ?>
            </div>
        <?php endif; ?>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div class="topbar-title"><?= Html::encode($this->title ?: 'Sistema Cajuí') ?></div>

            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="topbar-user">
                    <?= Html::encode($usuario->nome) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="page-content">
            <div class="card-page">

                <?php if (!empty($this->params['breadcrumbs'])): ?>
                    <?= Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'],
                    ]) ?>
                <?php endif ?>

                <?= Alert::widget([
                    'closeButton' => false,
                ]) ?>

                <?= $content ?>
            </div>
        </div>
    </main>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
