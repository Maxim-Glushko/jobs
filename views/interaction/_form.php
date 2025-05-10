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

    <?= $form->field($model, 'vacancy_id')->dropDownList(Vacancy::forSelect(), [
        'prompt' => 'Выберите вакансию',
        'class' => 'form-select',
        'id' => 'vacancy-id',
    ]) ?>

    <?= $form->field($model, 'person_ids')->dropDownList(Person::forSelect(), [
        'id' => 'person-ids',
        'multiple' => true,
        'class' => 'form-select',
        'value' => Yii::$app->request->isPost
            ? $model->person_ids
            : (empty($model->person_ids) ? $model->getPersons()->select('id')->column() : $model->person_ids),
    ])->label('Люди') ?>

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

    <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>