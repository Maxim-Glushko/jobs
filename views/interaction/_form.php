<?php

use app\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\Person;
use app\models\Interaction;
use app\models\Vacancy;
use yii\web\View;
use app\assets\ChoicesAsset;
use yii\helpers\ArrayHelper;

/**
 * @var View $this
 * @var Interaction $model
 * @var ActiveForm $form
 * @var string $date
 */

$this->registerJsFile('@web/js/interaction-form.js', ['depends' => [ChoicesAsset::class]]);

$additions = [
    ' отправил резюме ',
    ' в  work.ua ',
    ' в robota.ua ',
    ' в jobs.dou.ua ',
    ' в linkedin ',
    ' и на email ',
    ' и на telegram ',
    ' и в вайбер ',
];
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
                ? $model->person_ids // если из поста пришли, вставляем и пустой массив
                : ($model->person_ids ?: ArrayHelper::getColumn($model->persons, 'id')),
        ])->label('Люди') ?>

        <?= $form->field($model, 'date', ['options' => ['style' => 'flex:2']])->textInput([
             'type' => 'date',
            'value' => ($model->isNewRecord && empty($model->date)) ? date('Y-m-d') : $model->date,
            'class' => 'form-control form-control-lg'
        ]) ?>
    </div>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div style="display:flex;gap:1rem; padding-bottom: 16px;">
        <?= $form->field($model, 'result', ['options' => ['style' => 'flex:7']])->textarea(['rows' => 3]) ?>

        <div style="flex:5; padding-top:32px;">
            <?php foreach ($additions as $addition) { ?>
            <button type="button" class="btn btn-outline-primary btn-sm insert-template" style="margin: 0 3px 5px 0;" data-text="<?= $addition ?>">
                <?= $addition ?>
            </button>
            <?php } ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-secondary w-100 btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>