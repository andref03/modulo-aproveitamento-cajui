<?php

use yii\helpers\Html;

$this->title = 'Painel Inicial';
?>

<div class="site-index">
    <h1 class="mb-4">Painel Inicial</h1>

    <p class="text-muted mb-4">
        Sistema para gestão de solicitações de aproveitamento de estudos.
    </p>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h4>Solicitações</h4>
                <p>Gerencie solicitações de aproveitamento.</p>
                <?= Html::a('Acessar', ['/solicitacao-aproveitamento/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h4>Estudantes</h4>
                <p>Cadastre e consulte estudantes.</p>
                <?= Html::a('Acessar', ['/estudante/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h4>Coordenadores</h4>
                <p>Cadastre coordenadores por curso.</p>
                <?= Html::a('Acessar', ['/coordenador/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <div class="col-md-4 mt-3">
            <div class="card p-3 h-100">
                <h4>Cursos</h4>
                <p>Gerencie cursos cadastrados.</p>
                <?= Html::a('Acessar', ['/curso/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <div class="col-md-4 mt-3">
            <div class="card p-3 h-100">
                <h4>Disciplinas IFNMG</h4>
                <p>Gerencie a matriz de disciplinas.</p>
                <?= Html::a('Acessar', ['/disciplina-ifnmg/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <div class="col-md-4 mt-3">
            <div class="card p-3 h-100">
                <h4>Itens de Equivalência</h4>
                <p>Visualize os itens cadastrados.</p>
                <?= Html::a('Acessar', ['/item-equivalencia/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>