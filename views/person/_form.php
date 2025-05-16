<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Person;
use app\models\Company;
use app\assets\ChoicesAsset;
use yii\helpers\ArrayHelper;

/**
 * @var Person $model
 */

$this->registerJsFile('@web/js/person-form.js', ['depends' => [ChoicesAsset::class]]);
?>

<div class="person-form">
    <?php $form = ActiveForm::begin(); ?>

    <div style="display:flex;gap:1rem;">
        <?= $form->field($model, 'name', ['options' => ['style' => 'flex:5']])->textInput([
            'maxlength' => true,
            'class' => 'form-control form-control-lg'
        ]) ?>

        <?= $form->field($model, 'position', ['options' => ['style' => 'flex:3']])->textInput([
            'maxlength' => true,
            'placeholder' => '',
            'class' => 'form-control form-control-lg'
        ]) ?>

        <?= $form->field($model, 'company_ids', ['options' => ['style' => 'flex:5']])->dropDownList(Company::forSelect(), [
            'id' => 'company-ids',
            'multiple' => true,
            'class' => 'form-select',
            'value' => Yii::$app->request->isPost
                ? $model->company_ids
                : ($model->company_ids ?: ArrayHelper::getColumn($model->companies, 'id'))
        ]) ?>
    </div>

    <div style="display:flex; gap:1rem;padding:16px 0;">
        <?= $form->field($model, 'comment', ['options' => ['style' => 'flex:3']])->textarea(['rows' => 6]) ?>

        <div style="flex:2; padding-top:31px;">
            <?= $this->render('@app/views/shared/_contactsField', ['form' => $form, 'model' => $model]) ?>
        </div>
    </div>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-secondary w-100 btn-lg']) ?>

    <?php ActiveForm::end(); ?>
</div>