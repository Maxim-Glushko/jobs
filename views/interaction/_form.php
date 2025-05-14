<?php

use app\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\Company;
use app\models\Person;
use app\models\Interaction;
use app\models\Vacancy;
use yii\web\View;
use yii\web\JqueryAsset;

/**
 * @var View $this
 * @var Interaction $model
 * @var ActiveForm $form
 * @var string $date
 */

$this->registerCssFile('@web/css/choices.min.css');
$this->registerJsFile('@web/js/choices.min.js', ['depends' => [JqueryAsset::class]]);

$this->registerJs("
    var element = document.getElementById('vacancy-id');
    var choices = new Choices(element, {
        searchEnabled: true,
        removeItemButton: true,
    });
");
?>

<div class="interaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <div style="display:flex;gap:1rem">
        <?= $form->field($model, 'vacancy_id', ['options' => ['style' => 'flex:6']])->dropDownList(Vacancy::forSelect(), [
            'prompt' => 'Выберите вакансию',
            'class' => 'form-select',
            'id' => 'vacancy-id',
        ]) ?>

        <?= $form->field($model, 'person_ids', ['options' => ['style' => 'flex:6']])->dropDownList(Person::forSelect(), [
            'id' => 'person-ids',
            'multiple' => true,
            'class' => 'form-select',
            'value' => Yii::$app->request->isPost
                ? $model->person_ids
                : (empty($model->person_ids) ? $model->getPersons()->select('id')->column() : $model->person_ids),
        ])->label('Люди') ?>

        <?= $form->field($model, 'date', ['options' => ['style' => 'flex:2']])->textInput([
             'type' => 'date',
            'value' => ($model->isNewRecord && empty($model->date)) ? date('Y-m-d') : $model->date,
            'class' => 'form-control form-control-lg'
        ]) ?>
    </div>

    <?php
    $js = <<<JS
        $(document).ready(function() {
            const element = $('#person-ids')[0];
            const choices = new Choices(element, {
                removeItemButton: true,
                placeholderValue: 'Выберите людей',
                searchPlaceholderValue: 'Поиск...',
                noResultsText: 'Нет результатов',
                noChoicesText: 'Нет доступных людей',
            });
        });
    JS;
    $this->registerJs($js);
    ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'result')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-secondary w-100 btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>