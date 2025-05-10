<?php

namespace app\controllers;

use Yii;
use app\models\Company;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\models\CompanySearch;

class CompanyController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Список всех записей
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр записи
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание новой записи
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Обновление записи
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление записи
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * API метод для получения данных в формате JSON
     */
    public function actionApi($id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($id !== null) {
            $model = $this->findModel($id);
            return [
                'id' => $model->id,
                'name' => $model->name,
                'contacts' => $model->contacts,
                'comment' => $model->comment,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ];
        }

        $models = Company::find()->all();
        return array_map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'contacts' => $model->contacts,
                'comment' => $model->comment,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ];
        }, $models);
    }

    /**
     * Поиск модели по ID
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}