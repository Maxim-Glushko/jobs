<?php

use app\helpers\Html;
use yii\web\View;
use app\models\Interaction;

/**
 * @var View $this
 * @var Interaction $model
 */

/**
 * Update page title with interaction ID
 */
$this->title = 'Update Interaction: #' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Общение', 'url' => ['index']];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="interaction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>