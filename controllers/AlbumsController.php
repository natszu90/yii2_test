<?php

namespace app\controllers;

use Yii;
use app\models\Albums;
use app\models\AlbumsSearch;
use app\models\ApiLogs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AlbumsController implements the CRUD actions for Albums model.
 */
class AlbumsController extends Controller
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
     * Lists all Albums models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlbumsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Albums model.
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
     * Creates a new Albums model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Albums();
        $apiLogsModel = new ApiLogs();

        // load curl component
        $curl = Yii::$app->curl;
        
        if ($model->load(Yii::$app->request->post())) 
        {
            
            // create album
            $create_album = $curl->post('create_album',[
                'accessToken' => $curl->static_token,
                'title' => Yii::$app->request->post()['Albums']['title'],
                'description' => Yii::$app->request->post()['Albums']['description']
            ]);

            if($create_album['http_code'] == 200)
            {

                // get album
                $get_album = $curl->get('get_album',[
                    'accessToken' => $curl->static_token,
                    'album_id' => $create_album['response']->data->id
                ]);
                
                // save to albums table
                $model->album_id = $create_album['response']->data->id;
                $model->delete_hash = $create_album['response']->data->deletehash;
                $model->album_url = $get_album['response']->data->link;
                $model->save();
                
                // logs api request
                $apiLogsModel->request_url = $create_album['request_url'];
                $apiLogsModel->request_body = json_encode($create_album['request_body']);
                $apiLogsModel->response = json_encode($create_album['response']);
                $apiLogsModel->save();


                return $this->redirect(['.']);
                // return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Albums model.
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
     * Deletes an existing Albums model.
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
        $delete_album = $curl->delete('delete_album',[
            'accessToken' => $curl->static_token,
            'username' => $curl->username,
            'album_id' => $this->findModel($id)->album_id
        ]);

        // logs api request
        $apiLogsModel->request_url = $delete_album['request_url'];
        $apiLogsModel->request_body = json_encode($delete_album['request_body']);
        $apiLogsModel->response = json_encode($delete_album['response']);
        $apiLogsModel->save();
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Albums model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Albums the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Albums::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function api_logs($data = [])
    {

    }
}
