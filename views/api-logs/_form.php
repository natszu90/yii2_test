<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ApiLogs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="api-logs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'request_body')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'response')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'timestamp')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
