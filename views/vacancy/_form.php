<?php
use yii\bootstrap5\ActiveForm;
use app\helpers\Html;
use app\models\Vacancy;
use app\models\Company;
use yii\helpers\Json;
use yii\web\JqueryAsset;

$this->registerCssFile('@web/css/choices.min.css');
$this->registerJsFile('@web/js/choices.min.js', ['depends' => [JqueryAsset::class]]);

$this->registerJs("
    var element = document.getElementById('company-id');
    var choices = new Choices(element, {
        searchEnabled: true,
        removeItemButton: true,
        placeholderValue: 'Выберите компанию'
    });
");

/**
 * @var Vacancy $model
 */
?>

<div class="vacancy-form">
    <?php $form = ActiveForm::begin(); ?>

    <div style="display:flex;gap:1rem">
        <?= $form->field($model, 'title', ['options' => ['style' => 'flex:1']])
            ->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>

        <?= $form->field($model, 'company_id', ['options' => ['style' => 'flex:1']])->dropDownList(Company::forSelect(), [
            'prompt' => 'Выберите компанию',
            'class' => 'form-select',
            'id' => 'company-id',
        ]) ?>
    </div>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div style="display:flex;gap:1rem">
        <?= $this->render('@app/views/shared/_contactsField', ['form' => $form, 'model' => $model]) ?>

        <?= $form->field($model, 'comment', ['options' => ['style' => 'flex:1']])->textarea(['rows' => 6]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-secondary w-100 btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>