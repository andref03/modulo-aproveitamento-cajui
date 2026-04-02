<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DisciplinaIfnmg $model */

$this->title = 'Create Disciplina Ifnmg';
$this->params['breadcrumbs'][] = ['label' => 'Disciplina Ifnmgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disciplina-ifnmg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
