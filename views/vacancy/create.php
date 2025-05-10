<?php

use app\helpers\Html;
use yii\web\View;
use app\models\Vacancy;

/**
 * @var View $this
 * @var Vacancy $model
 */

$this->title = 'Создать вакансию';
$this->params['breadcrumbs'][] = ['label' => 'Вакансии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacancy-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>