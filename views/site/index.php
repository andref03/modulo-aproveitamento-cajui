<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'Módulo de Aproveitamento de Estudos';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Módulo de Aproveitamento de Estudos</h1>

        <p class="lead">
            Sistema para gerenciamento de solicitações de aproveitamento de disciplinas.
        </p>
    </div>

    <div class="body-content">
        <div class="row">

            <div class="col-md-4 mb-4">
                <div class="card p-3 shadow-sm" style="border-radius:10px; min-height:180px;">
                    <h3>Solicitações</h3>
                    <p>Gerenciar solicitações de aproveitamento de estudos.</p>
                    <?= Html::a('Acessar', ['/solicitacao-aproveitamento/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card p-3 shadow-sm" style="border-radius:10px; min-height:180px;">
                    <h3>Itens de Equivalência</h3>
                    <p>Visualizar e editar os itens vinculados às solicitações.</p>
                    <?= Html::a('Acessar', ['/item-equivalencia/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card p-3 shadow-sm" style="border-radius:10px; min-height:180px;">
                    <h3>Estudantes</h3>
                    <p>Cadastro e manutenção dos estudantes.</p>
                    <?= Html::a('Acessar', ['/estudante/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card p-3 shadow-sm" style="border-radius:10px; min-height:180px;">
                    <h3>Coordenadores</h3>
                    <p>Cadastro dos coordenadores responsáveis pelas análises.</p>
                    <?= Html::a('Acessar', ['/coordenador/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card p-3 shadow-sm" style="border-radius:10px; min-height:180px;">
                    <h3>Cursos</h3>
                    <p>Gerenciar os cursos vinculados ao sistema.</p>
                    <?= Html::a('Acessar', ['/curso/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card p-3 shadow-sm" style="border-radius:10px; min-height:180px;">
                    <h3>Disciplinas IFNMG</h3>
                    <p>Cadastro das disciplinas de destino para equivalência.</p>
                    <?= Html::a('Acessar', ['/disciplina-ifnmg/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

        </div>
    </div>
</div>