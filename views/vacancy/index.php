<?php

use app\helpers\Html;
use yii\grid\GridView;
use app\models\VacancySearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Url;

/**
 * @var View $this
 * @var VacancySearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'Вакансии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacancy-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать вакансию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //'id',
            'title',
            'text',
            [
                'label' => 'Компания',
                'attribute' => 'company_name',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->company) {
                        return Html::companyLink($model->company);
                    }
                    return '';
                }
            ],
            [
                'label' => 'Люди',
                'format' => 'raw',
                'value' => function ($model) {
                    $html ='';
                    if ($model->company && $model->company->persons) {
                        foreach ($model->company->persons as $person) {
                            $html .= Html::personLink($person) . '<br />';
                        }
                    }
                    return $html;
                }
            ],
            [
                'attribute' => 'contacts',
                'format' => 'raw',
                'value' => function ($model) {
                    if (empty($model->contacts)) {
                        return '';
                    }
                    $html = '<ul class="list-unstyled mb-0">';
                    foreach ($model->contacts as $type => $value) {
                        $html .= '<li><strong>' . Html::encode($type) . ':</strong> ' . Html::encode($value) . '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                },
                'filter' => Html::activeTextInput($searchModel, 'contacts', [
                    'class' => 'form-control',
                    'value' => !empty($searchModel->contacts) && is_array($searchModel->contacts)
                        ? Json::encode($searchModel->contacts)
                        : '',
                ]),
            ],
            'comment',
            //'created_at',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
</div>