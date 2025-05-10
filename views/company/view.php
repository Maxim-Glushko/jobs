<?php

use app\helpers\Html;
use yii\widgets\DetailView;
use yii\web\View;
use app\models\Company;
use yii\helpers\Url;

/**
 * @var View $this
 * @var Company $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-primary']) ?>
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
                    $html = '';
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
                    $html = '';
                    if ($model->persons) {
                        foreach ($model->persons as $person) {
                            $html .= Html::personLink($person) . '<br />';
                        }
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
