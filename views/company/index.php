<?php

use app\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\web\View;
use app\models\CompanySearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * @var $this View
 * @var $searchModel CompanySearch
 * @var $dataProvider ActiveDataProvider
 */

$this->title = 'Компании';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить компанию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            'name',
            [
                'attribute' => 'contacts',
                'enableSorting' => false,
                'format' => 'raw',
                'value' => function($model) {
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
            ],
            [
                'label' => 'Вакансии',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = '';
                    if ($model->vacancies) {
                        foreach ($model->vacancies as $vacancy) {
                            $html .= Html::vacancyLink($vacancy) .'<br />';
                        }
                    }
                    return $html;
                }
            ],
            [
                'label' => 'Люди',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = '';
                    if ($model->persons) {
                        foreach ($model->persons as $person) {
                            $html .= Html::personLink($person) . '<br />';
                        }
                    }
                    return $html;
                }
            ],
            'comment',
            //'created_at:datetime',
            //'updated_at:datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
