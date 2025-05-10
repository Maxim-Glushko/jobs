<?php

namespace app\controllers;

use Yii;
use app\models\Vacancy;
use app\models\VacancySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class VacancyController extends BaseController
{
    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors(): array
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
     * Lists all Vacancy records
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new VacancySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Views a single Vacancy record
     * @param int $id ID of the record to view
     * @return string
     * @throws NotFoundHttpException
     */

    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Vacancy record
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new Vacancy();

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
     * Updates an existing Vacancy record
     * @param int $id ID of the record to update
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
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
     * Deletes an existing Vacancy record
     * @param int $id ID of the record to delete
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * API method for getting data in JSON format
     * @param int|null $id Optional ID of specific record to return
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionApi($id = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($id !== null) {
            $model = $this->findModel($id);
            return [
                'id' => $model->id,
                'title' => $model->title,
                'description' => $model->description,
                'salary' => $model->salary,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ];
        }

        $models = Vacancy::find()->all();
        return array_map(function ($model) {
            return [
                'id' => $model->id,
                'title' => $model->title,
                'description' => $model->description,
                'salary' => $model->salary,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ];
        }, $models);
    }

    /**
     * Finds the Vacancy model based on its primary key value
     * @param int $id ID of the model to find
     * @return Vacancy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Vacancy
    {
        if (($model = Vacancy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}