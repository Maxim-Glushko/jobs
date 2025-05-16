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

    <div style="display:flex;gap:1rem">
        <?= $form->field($model, 'status', ['options' => ['style' => 'flex:1']])->dropDownList(Company::$statuses, [
            'class' => 'form-select',
            'id' => 'status-id',
            'encode' => false
        ]) ?>

        <?= $form->field($model, 'name', ['options' => ['style' => 'flex:17']])->textInput() ?>
    </div>

    <div style="display:flex;gap:1rem;padding:16px 0;">
        <?= $form->field($model, 'comment', ['options' => ['style' => 'flex:3']])->textarea(['rows' => 6]) ?>

        <div style="flex:2; padding-top:24px;">
            <?= $this->render('@app/views/shared/_contactsField', ['form' => $form, 'model' => $model]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-secondary w-100 btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>