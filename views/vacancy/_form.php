<?php
use yii\bootstrap5\ActiveForm;
use app\helpers\Html;
use app\models\Vacancy;
use app\models\Company;
use yii\helpers\Json;
use app\assets\ChoicesAsset;

/**
 * @var Vacancy $model
 */

$this->registerJsFile('@web/js/vacancy-form.js', ['depends' => [ChoicesAsset::class]]);
?>

<div class="vacancy-form">
    <?php $form = ActiveForm::begin(); ?>

    <div style="display:flex;gap:1rem">
        <?= $form->field($model, 'title', ['options' => ['style' => 'flex:3']])
            ->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>

        <?= $form->field($model, 'company_id', ['options' => ['style' => 'flex:3']])->dropDownList(Company::forSelect(), [
            'prompt' => 'Выберите компанию',
            'class' => 'form-select',
            'id' => 'company-id',
        ]) ?>

        <?= $form->field($model, 'interview_date', ['options' => ['style' => 'flex:1']])->textInput([
            'type' => 'date',
            'value' => ($model->isNewRecord && empty($model->interview_date)) ? '' : $model->interview_date,
            'class' => 'form-control form-control-lg'
        ]) ?>
    </div>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div style="display:flex;gap:1rem;padding:16px 0;">
        <?= $form->field($model, 'comment', ['options' => ['style' => 'flex:3']])->textarea(['rows' => 6]) ?>

        <div style="flex:2; padding-top:31px;">
            <?= $this->render('@app/views/shared/_contactsField', ['form' => $form, 'model' => $model]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-secondary w-100 btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>