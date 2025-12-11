<?php

use app\helpers\Html;
use yii\web\View;
use app\models\Interaction;
use app\models\Vacancy;

/**
 * @var View $this
 * @var Interaction $model
 * @var Vacancy $vacancy
 */

$this->title = 'Добавить взаимодействие';
//$this->params['breadcrumbs'][] = ['label' => 'Общение', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="interaction-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>