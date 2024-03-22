<?php

namespace app\controllers;

use app\models\Request;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['*'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'create'],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['cancel', 'success'],
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action){
                                $model = Request::findOne(Yii::$app->request->get('id'));
                                return Yii::$app->user->identity->isAdmin() && $model->status == 0;
                            }
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }
    /**
     * Lists all Request models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Request::find()->where(['id_user' => Yii::$app->user->getId()])
            ->orderBy('dateTime DESC'),
            ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Request model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Request();

        if ($model->load(Yii::$app->request->post())){
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->validate() && $model->upload()){
                $model->save(false);
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Request model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCancel($id)
    {
        $model = Request::findOne($id);
        $model->scenario = 'cancel';

        if ($model->load(Yii::$app->request->post()) && $model->cancel()){
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('cancel', [
            'model' => $model,
        ]);
    }
    public function actionSuccess($id)
    {
        $model = $this->findModel($id);

        $model->scenario = 'success';

        if (Yii::$app->request->post()){
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->success()){
                return $this->redirect(['admin/index']);
            }
        }

        return $this->render('success', [
            'model' => $model,
        ]);
    }
}
