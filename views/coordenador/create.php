<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Coordenador $model */

$this->title = 'Create Coordenador';
$this->params['breadcrumbs'][] = ['label' => 'Coordenadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coordenador-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
