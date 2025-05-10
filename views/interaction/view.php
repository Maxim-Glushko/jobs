<?php

use app\helpers\Html;
use yii\widgets\DetailView;
use yii\web\View;
use app\models\Interaction;
use yii\helpers\Url;

/**
 * @var View $this
 * @var Interaction $model
 */

$this->title = '#' . $model->id . ' - ' . Yii::$app->formatter->asDate($model->date, 'php:d M Y') . ' - ' . $model->vacancy->title . ' (' . $model->vacancy->company->name . ')';
$this->params['breadcrumbs'][] = ['label' => 'Общение', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Вакансия',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->vacancy) {
                        return '';
                    }
                    return Html::vacancyLink($model->vacancy);
                }
            ],
            [
                'label' => 'Компания',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->vacancy || !$model->vacancy->company) {
                        return '';
                    }
                    return Html::companyLink($model->vacancy->company);
                }
            ],
            [
                'label' => 'Сотрудники',
                'format' => 'raw',
                'value' => function ($model) {
                    return implode('<br>', array_map(function ($person) {
                        return Html::personLink($person);
                    }, $model->persons));
                }
            ],
            'date:date',
            'text:ntext',
            'result:ntext',
        ],
    ]) ?>

</div>