<?php

use app\helpers\Html;
use app\helpers\Icon;
use yii\grid\GridView;
use app\models\VacancySearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JqueryAsset;

/**
 * @var View $this
 * @var VacancySearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'Вакансии';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/vacancy-index.js', ['depends' => [JqueryAsset::class]]);
?>

<div class="vacancy-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                'attribute' => 'title',
                'label' => 'Заголовок/Контакты',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a(Html::encode($model->title), Url::toRoute(['vacancy/view', 'id' => $model->id]))
                        . Html::contactsList($model);
                }
            ],
            [
                'attribute' => 'text',
                'format' => 'raw',
                'value' => function ($model) {
                    $shortText = mb_substr($model->text, 0, 200);
                    $isTruncated = mb_strlen($model->text) > 200;
                    $html = '<div class="text-toggle">
                        <p class="short-text">' . nl2br(Html::encode($shortText)) . ($isTruncated ? '…' : '') . '</p>';
                    if ($isTruncated) {
                        $html .= '<p class="full-text d-none">' . nl2br(Html::encode($model->text)) . '</p>'
                            . '<a href="#" class="toggle-link" style="text-decoration-style: dashed;">далее</a>';
                    }
                    return $html . '</div>';
                }
            ],
            [
                'label' => 'Компания/Люди',
                'attribute' => 'company_name',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = '';
                    if ($model->company) {
                        $html .= Html::companyLink($model->company);

                        if ($model->company->persons) {
                            $html .= '<ul>';
                            foreach ($model->company->persons as $person) {
                                $html .= '<li>' . Html::personLink($person) . '</li>';
                            }
                            $html .= '</ul>';
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
                    $person_ids = $model->company && $model->company->persons ? array_column($model->company->persons, 'id') : [];
                    $html = Html::createInteractionLink($model->id, $person_ids);
                    if ($model->interactions) {
                        $interactions = $model->interactions;
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
                    return $html;
                }
            ],
            'comment',
            //'created_at',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>