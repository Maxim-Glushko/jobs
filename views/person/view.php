<?php
use app\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\View;
use app\models\Person;

/**
 * @var $this View
 * @var $model Person
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="person-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого сотрудника?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('К списку', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

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
                    foreach ($model->interactions as $interaction) {
                        $html .= '<a href="' . Url::to(['/interaction','id' => $interaction->id]) . '">#' . Html::encode($interaction->id) . ' / ' . Yii::$app->formatter->asDate($interaction->date, 'php:d M Y') . '</a> ';
                        if ($interaction->vacancy) {
                            $html .= ' <- ' . Html::vacancyLink($interaction->vacancy);
                            if ($interaction->vacancy->company) {
                                $html .= ' <- ' . Html::companyLink($interaction->vacancy->company);
                            }
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
