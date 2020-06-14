<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ApiLogs */

$this->title = 'Create Api Logs';
$this->params['breadcrumbs'][] = ['label' => 'Api Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-logs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
