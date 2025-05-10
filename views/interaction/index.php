<?php

use app\models\Interaction;
use app\models\InteractionSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\helpers\Html;
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

    <p>
        <?= Html::a('Создать взаимодействие', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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