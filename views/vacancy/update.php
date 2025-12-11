<?php
use app\helpers\Html;
use app\models\Vacancy;

/**
 * @var Vacancy $model
 */

$this->title = 'Изменение данных: ' . $model->title;
//$this->params['breadcrumbs'][] = ['label' => 'Вакансии', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="vacancy-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>