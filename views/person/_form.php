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
                : (empty($model->company_ids) ? $model->getCompanies()->select('id')->column() : $model->company_ids)
        ]) ?>
    </div>

    <div style="display:flex; gap:1rem;">
        <?= $this->render('@app/views/shared/_contactsField', ['form' => $form, 'model' => $model]) ?>

        <?= $form->field($model, 'comment', ['options' => ['style' => 'flex:1']])->textarea([
            'rows' => 6
        ]) ?>
    </div>

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

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-secondary w-100 btn-lg']) ?>

    <?php ActiveForm::end(); ?>
</div>