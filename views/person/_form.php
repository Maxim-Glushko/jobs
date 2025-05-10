<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Json;
use app\models\Person;
use app\models\Company;
use yii\web\JqueryAsset;

/**
 * @var Person $model
 */

$this->registerCssFile('@web/css/choices.min.css');
$this->registerJsFile('@web/js/choices.min.js', ['depends' => [JqueryAsset::class]]);
?>

<div class="person-form">
    <?php $form = ActiveForm::begin([
        'id' => 'person-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-sm-2 col-form-label'],
            'inputOptions' => ['class' => 'col-sm-6 form-control'],
            'errorOptions' => ['class' => 'col-sm-4 invalid-feedback'],
        ],
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'mt-3'
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput([
        'maxlength' => true,
    ]) ?>

    <?= $form->field($model, 'position')->textInput([
        'maxlength' => true,
        'placeholder' => ''
    ]) ?>

    <?= $this->render('@app/views/shared/_contactsField', ['form' => $form, 'model' => $model]) ?>

    <?= $form->field($model, 'comment')->textarea([
        'rows' => 4,
        'placeholder' => 'Дополнительная информация'
    ]) ?>

    <?= $form->field($model, 'company_ids')->dropDownList(Company::forSelect(), [
        'id' => 'company-ids',
        'multiple' => true,
        'class' => 'form-select',
        'value' => Yii::$app->request->isPost
            ? $model->company_ids
            : (empty($model->company_ids) ? $model->getCompanies()->select('id')->column() : $model->company_ids),
    ]) ?>

    <?php
    $js = <<<JS
        $(document).ready(function() {
            const element = $('#company-ids')[0];
            const choices = new Choices(element, {
                removeItemButton: true,
                placeholderValue: 'Выберите компании',
                searchPlaceholderValue: 'Поиск...',
                noResultsText: 'Нет результатов',
                noChoicesText: 'Нет доступных компаний',
            });
        });
    JS;
    $this->registerJs($js);
    ?>

    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
            <?= Html::submitButton(
                $model->isNewRecord ? 'Создать' : 'Обновить',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>