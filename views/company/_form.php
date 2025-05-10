<?php

use app\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use yii\web\View;
use app\models\Company;

/**
 * @var $this View
 * @var $model Company
 * @var $form ActiveForm
 */
?>

<div class="company-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $this->render('@app/views/shared/_contactsField', ['form' => $form, 'model' => $model]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>