<?php

use app\helpers\Html;
use app\helpers\Icon;
use yii\widgets\DetailView;
use yii\web\View;
use app\models\Company;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\helpers\ArrayHelper;

/**
 * @var View $this
 * @var Company $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="company-view">

    <p class="float-end">
        <?php /*= Html::a('Список', ['index'], ['class' => 'btn btn-primary']) */ ?>
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
            'name',
            [
                'attribute' => 'contacts',
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
                    $html = Html::createVacancyLink($model->id);
                    if ($model->vacancies) {
                        foreach ($model->vacancies as $vacancy) {
                            $html .= Html::vacancyLink($vacancy) . '<br />';
                        }
                    }
                    return $html;
                }
            ],
            [
                'label' => 'Люди',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = Html::createPersonLink($model->id);;
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
                                    $html .= Html::a(Yii::$app->formatter->asDate($interaction->date, 'php:d M Y'),
                                        Url::toRoute(['/interaction/view', 'id' => $interaction->id]));
                                    if ($interaction->result) {
                                        $html .= ': ' . Html::encode($interaction->result);
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
            //'created_at',
            //'updated_at',
        ],
    ]) ?>

</div>
