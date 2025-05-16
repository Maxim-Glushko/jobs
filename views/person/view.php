<?php
use app\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\View;
use app\models\Person;
use yii\helpers\ArrayHelper;

/**
 * @var $this View
 * @var $model Person
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="person-view">
    <p class="float-end">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого сотрудника?',
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
            'position',
            [
                'attribute' => 'contacts',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::contactsList($model);
                },
            ],
            [
                'label' => 'Компании',
                'format' => 'raw',
                'value' => function($model) {
                    if (empty($model->companies)) {
                        return '';
                    }
                    $html = '';
                    foreach ($model->companies as $company) {
                        $html .= Html::companyLink($company) . '<br />';
                    }
                    return $html;
                }
            ],
            [
                'label' => 'Общение',
                'format' => 'raw',
                'value' => function($model) {
                    if (empty($model->interactions)) {
                        return '';
                    }
                    $html = '';
                    $interactions = $model->interactions;
                    ArrayHelper::multisort($interactions, 'date', SORT_DESC);
                    foreach ($interactions as $interaction) {
                        $block = '<a href="' . Url::toRoute(['/interaction/view','id' => $interaction->id]) . '">'
                            . Yii::$app->formatter->asDate($interaction->date, 'php:d M Y') . '</a> '
                            . Html::encode($interaction->result);
                        if ($interaction->vacancy) {
                            $block = Html::vacancyLink($interaction->vacancy) . ' ⇋ ' . $block;
                            if ($interaction->vacancy->company) {
                                $block = Html::companyLink($interaction->vacancy->company) . ' ⇋ ' . $block;
                            }
                            $html .= $block;
                        }
                        $html .= '<br />';
                    }
                    return $html;
                }
            ],
            'comment:ntext',
        ],
    ]) ?>
</div>
