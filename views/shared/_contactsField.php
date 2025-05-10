<?php

use yii\widgets\ActiveForm;
use yii\helpers\Json;
use app\models\Person;
use app\models\Company;
use app\models\Vacancy;

/**
 * @var $model Person| Company | Vacancy
 * @var $form ActiveForm
 */
?>

<?= $form->field($model, 'contacts', [
    'enableClientValidation' => true,
    'enableAjaxValidation' => false
])->textarea([
    'rows' => 6,
    'class' => 'form-control json-validator',
    'placeholder' => "{\n  \"email\": \"example@mail.com\",\n  \"phone\": \"+38 063 123-45-67\",\n  \"telegram\": \"@username\"\n  \"url\": \"https://site.ua\"\n}",
    'value' => is_array($model->contacts) && !empty($model->contacts)
        ? Json::encode($model->contacts, JSON_PRETTY_PRINT)
        : ''
])->hint('Введите контакты в формате JSON') ?>