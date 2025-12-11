<?php

use app\helpers\Html;
use yii\widgets\DetailView;
use app\models\Vacancy;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @var View $this
 * @var Vacancy $model
 */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => 'Вакансии', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacancy-view">
    <p class="float-end">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'title',
            [
                'label' => 'Компания',
                'format' => 'raw',
                'attribute' => 'company_id',
                'value' => function($model) {
                    return $model->company ? Html::companyLink($model->company) : '';
                }
            ],
            'text:ntext',
            [
                'attribute' => 'contacts',
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function ($model) {
                    return Html::contactsList($model);
                },
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
            'comment:ntext',
        ],
    ]) ?>
</div>