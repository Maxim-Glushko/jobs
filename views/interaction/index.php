<?php

use app\helpers\Html;
use app\helpers\Icon;
use app\models\Interaction;
use app\models\InteractionSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\web\View;
use yii\helpers\Url;

/**
 * @var View $this
 * @var InteractionSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'Общение';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interaction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'maxButtonCount' => 5,
            'options' => ['class' => 'pagination justify-content-center'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
            'class' => 'yii\widgets\LinkPager',
        ],
        'layout' => '<div class="d-flex justify-content-between align-items-center mb-3">
                <div>{summary}</div>
                <div>{pager}</div>   
                <div>' . Html::a(Icon::svg('plus'), ['create'], ['class' => 'float-end fs-1']) . '</div>
            </div>
            {items}
            <div class="d-flex justify-content-center mt-3">{pager}</div>',
        'columns' => [
            //'id',
            [
                'label' => 'Вакансия',
                'attribute' => 'vacancy_title',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->vacancy) {
                        return Html::vacancyLink($model->vacancy);
                    }
                    return '';
                }
            ],
            [
                'label' => 'Компания',
                'attribute' => 'company_name',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->vacancy && $model->vacancy->company) {
                        return Html::companyLink($model->vacancy->company);
                    }
                    return '';
                }
            ],
            [
                'label' => 'Люди',
                'attribute' => 'person_name',
                'format' => 'raw',
                'value' => function ($model) {
                    $return = '';
                    if ($model->persons) {
                        foreach ($model->persons as $person) {
                            $return .= Html::personLink($person) .'<br />';
                        }
                    }
                    return $return;
                }
            ],
            'text:ntext',
            'result:ntext',
            'date:date',

            ['class' => ActionColumn::class],
        ],
    ]); ?>

</div>