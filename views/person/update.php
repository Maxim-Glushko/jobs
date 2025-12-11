<?php
use app\helpers\Html;
use app\models\Person;

/**
 * @var Person $model
 */

$this->title = 'Изменение данных: ' . $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="person-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>