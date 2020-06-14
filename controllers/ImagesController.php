<?php

namespace app\controllers;

use Yii;
use app\models\Images;
use app\models\ImagesSearch;
use app\models\Albums;
use app\models\ApiLogs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


/**
 * ImagesController implements the CRUD actions for Images model.
 */
class ImagesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Images models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataAlbums = Albums::find()->asArray()->all();
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'AlbumsRecord' => (!empty($dataAlbums)) ? 1 : 0

        ]);
    }

    /**
     * Displays a single Images model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Images model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Images();
        $apiLogsModel = new ApiLogs();
        $items =  ArrayHelper::map(Albums::find()->all(), 'id', 'album_id');

        // load curl component
        $curl = Yii::$app->curl;

        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image_id');
            $album_id = Yii::$app->request->post()['Images']['album_id'];
            $album = Albums::find()->where(['id' => $album_id])->asArray()->one();
           

            // upload image
            $upload_image = $curl->post('upload_image',[
                'accessToken' => $curl->static_token,
                'image' => base64_encode(file_get_contents($model->image->tempName)),
                'album' => $album['album_id']
            ]);

            // save to images table
            $model->album_id = $album_id;
            $model->image_id = $upload_image['response']->data->id;
            $model->image_delete_hash = $upload_image['response']->data->deletehash;
            $model->image_url = $upload_image['response']->data->link;
            $model->save();
            
            // logs api request
            $apiLogsModel->request_url = $upload_image['request_url'];
            $apiLogsModel->request_body = json_encode($upload_image['request_body']);
            $apiLogsModel->response = json_encode($upload_image['response']);
            $apiLogsModel->save();

            return $this->redirect(['.']);
           
        }

        return $this->render('create', [
            'model' => $model,
            'items' => $items
        ]);
    }

    /**
     * Updates an existing Images model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // no functionality implemented for update
        return $this->redirect(['.']);
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Images model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $apiLogsModel = new ApiLogs();

        // load curl component
        $curl = Yii::$app->curl;

        // delete album
        $delete_image = $curl->delete('delete_image',[
            'accessToken' => $curl->static_token,
            'image_id' => $this->findModel($id)->image_id
        ]);


        // logs api request
        $apiLogsModel->request_url = $delete_image['request_url'];
        $apiLogsModel->request_body = json_encode($delete_image['request_body']);
        $apiLogsModel->response = json_encode($delete_image['response']);
        $apiLogsModel->save();

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Images model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Images the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Images::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
