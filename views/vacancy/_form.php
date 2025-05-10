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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'company_id')->dropDownList(Company::forSelect(), [
        'prompt' => 'Выберите компанию',
        'class' => 'form-select',
        'id' => 'company-id',
    ]) ?>

    <?= $this->render('@app/views/shared/_contactsField', ['form' => $form, 'model' => $model]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>