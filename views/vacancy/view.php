<?php

use app\helpers\Html;
use yii\widgets\DetailView;
use app\models\Vacancy;

use yii\web\View;

/** @var View $this */
/** @var Vacancy $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Вакансии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacancy-view">
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
            'comment:ntext',
        ],
    ]) ?>
</div>