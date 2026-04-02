<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DisciplinaIfnmg $model */

$this->title = 'Nova Disciplina';
$this->params['breadcrumbs'][] = ['label' => 'Disciplinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disciplina-ifnmg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
