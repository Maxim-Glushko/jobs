<?php

use app\helpers\Html;
use app\helpers\Icon;
use app\models\Person;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Json;
use yii\web\View;
use app\models\PersonSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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
        <?= Html::a(Icon::svg('plus'), ['create'], ['class' => 'float-end fs-1']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Имя/Должность/Контакты',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = Html::personLink($model);
                    if ($model->position) {
                        $html .= ' - ' . Html::encode($model->position);
                    }
                    if (!empty($model->contacts)) {
                        $html .= '<ul class="list-unstyled mb-0">';
                        foreach ($model->contacts as $type => $value) {
                            $html .= '<li><strong>' . Html::encode($type) . ':</strong> ' . Html::encode($value) . '</li>';
                        }
                        $html .= '</ul>';
                    }
                    return $html;
                },
            ],
            [
                'label' => 'Компании/Вакансии',
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
            [
                'label' => 'Общение',
                'attribute' => 'latestInteractionDate',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = '';
                    if ($model->interactions) {
                        $interactions = $model->interactions;
                        ArrayHelper::multisort($interactions, 'date', SORT_DESC);
                        foreach ($interactions as $interaction) {
                            if ($interaction->vacancy && in_array($interaction->vacancy->company_id, ArrayHelper::getColumn($model->companies, 'id'))) {
                                $html .= '<a href="' . Url::toRoute(['/interaction/view', 'id' => $interaction->id]) . '">'
                                    . '#' . $interaction->id . ' - ' . Yii::$app->formatter->asDate($interaction->date, 'php:d M Y')
                                    . '</a>';
                                if ($interaction->result) {
                                    $html .= ':<br />' . Html::encode($interaction->result);
                                }
                                $html .= '<br />';
                            }
                        }
                    }
                    return $html;
                }
            ],
            'comment',
            //'created_at:datetime',
            //'updated_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>