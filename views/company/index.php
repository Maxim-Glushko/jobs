<?php

use app\helpers\Html;
use app\helpers\Icon;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\web\View;
use app\models\CompanySearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

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
        <?= Html::a(Icon::svg('plus'), ['create'], ['class' => 'float-end fs-1']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute' => 'name',
                'label' => 'Компания',
                'format' => 'raw',
                'value' => function($model) {
                    $html = '<h4>' . Html::a(Html::encode($model->name), Url::toRoute(['company/view', 'id' => $model->id])) . '</h4>';
                    if (!empty($model->contacts)) {
                        $html .= '<ul class="list-unstyled mb-0">';
                        foreach ($model->contacts as $type => $value) {
                            $html .= '<li><strong>' . Html::encode($type) . ':</strong> ' . Html::encode($value) . '</li>';
                        }
                        $html .= '</ul>';
                    }
                    return $html;
                }
            ],
            [
                'label' => 'Вакансии',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = Html::createVacancyLink($model->id);
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
                    $html = Html::createPersonLink($model->id);
                    if ($model->persons) {
                        foreach ($model->persons as $person) {
                            $html .= Html::personLink($person) . '<br />';
                        }
                    }
                    return $html;
                }
            ],
            [
                'label' => 'Общение',
                'format' => 'raw',
                'attribute' => 'latestInteractionDate',
                'value' => function ($model) {
                    $person_ids = $model->persons ? array_column($model->persons, 'id') : [];
                    $html = '';
                    if ($model->vacancies) {
                        foreach ($model->vacancies as $vacancy) {
                            $html .= Html::createInteractionLink($vacancy->id, $person_ids);
                            if ($vacancy->interactions) {
                                $interactions = $vacancy->interactions;
                                ArrayHelper::multisort($interactions, ['date', 'created_at'], [SORT_DESC, SORT_DESC]);
                                foreach ($interactions as $interaction) {
                                    $html .= '<a href="' . Url::toRoute(['/interaction/view', 'id' => $interaction->id]) . '">'
                                        . Yii::$app->formatter->asDate($interaction->date, 'php:d M Y')
                                        . '</a>';
                                    if ($interaction->result) {
                                        $html .= ':<br />' . Html::encode($interaction->result);
                                    }
                                    $html .= '<br />';
                                }
                            }
                        }
                    } else {
                        // $html .= Html::createInteractionLink(0, $person_ids);
                    }
                    return $html;
                }
            ],
            'comment:ntext',
            //'created_at:datetime',
            //'updated_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],

        ],
    ]); ?>
</div>
