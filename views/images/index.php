<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ImagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Images';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="images-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if($AlbumsRecord) { ?>
    <p>
        <?= Html::a('Create Images', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php } ?>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'album_id:ntext',
            'image_id:ntext',
            'image_delete_hash:ntext',
            'image_url:ntext',
            //'timestamp',

            ['class' => 'yii\grid\ActionColumn','template'=>'{create} {view} {delete}'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
