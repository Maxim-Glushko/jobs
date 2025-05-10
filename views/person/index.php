<?php

use app\models\Person;
use app\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Json;
use yii\web\View;
use app\models\PersonSearch;
use yii\data\ActiveDataProvider;

/**
 * @var $this View
 * @var $searchModel PersonSearch
 * @var $dataProvider ActiveDataProvider
 */

$this->title = 'Люди';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить людя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'name',
            'position',
            [
                'attribute' => 'contacts',
                'format' => 'raw',
                'enableSorting' => false,
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
                    'class' => 'form-control'
                ]),
            ],
            [
                'label' => 'Компании: Вакансии',
                'format' => 'raw',
                //'enableSorting' => false,
                'value' => function ($model) {
                    if (empty($model->companies)) {
                        return '';
                    }
                    $html = '<ul class="list-unstyled mb-0">';
                    foreach ($model->companies as $company) {
                        $html .= Html::companyLink($company);
                        if ($company->vacancies) {
                            $html .= "<ul>";
                            foreach ($company->vacancies as $vacancy) {
                                $html .= '<li>' . Html::vacancyLink($vacancy) . '</li>';
                            }
                            $html .= "</ul>";
                        }
                        $html .= '<br />';
                    }
                    $html .= '</ul>';
                    return $html;
                }
            ],
            'comment',
            //'created_at:datetime',
            //'updated_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>