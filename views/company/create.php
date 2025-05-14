<?php

use app\helpers\Html;
use yii\web\View;
use app\models\Company;

/**
 * @var View $this
 * @var Company $model
 */

$this->title = 'Добавить компанию';
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>