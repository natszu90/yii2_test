<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Images */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="images-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>

    <?= $form->field($model, 'image_id')->fileInput() ?>

    <?= $form->field($model, 'album_id')
        ->dropDownList(
            $items,           // Flat array ('id'=>'label')
            ['prompt'=>'']    // options
        );
    ?>
   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
