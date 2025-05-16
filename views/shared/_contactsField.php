<?php

use yii\widgets\ActiveForm;
use yii\helpers\Json;
use app\models\Person;
use app\models\Company;
use app\models\Vacancy;
use yii\web\JqueryAsset;

/**
 * @var $model Person| Company | Vacancy
 * @var $form ActiveForm
 */

$this->registerJsFile('@web/js/_contacts-form.js', ['depends' => [JqueryAsset::class]]);

if (empty($model->contacts))
    $model->contacts = [];
if (is_array($model->contacts))
    $model->contacts = Json::encode($model->contacts);

$contactTypes = [
    'url'       => 'URL',
    'email'     => 'Email',
    'phone'     => 'Phone',
    'telegram'  => 'Telegram',
    'instagram' => 'Instagram',
    'address'   => 'Address'
];
?>

<?= $form->field($model, 'contacts', [
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'contacts-container']
])->hiddenInput(['class' => 'contacts-json-input'])->label(false) ?>

<div class="dynamic-contacts-container">
    <div class="contact-fields"></div>

    <select class="form-select contact-type-select">
        <option value="">Добавить контакт</option>
        <?php foreach ($contactTypes as $value => $label): ?>
            <option value="<?= $value ?>"><?= $label ?></option>
        <?php endforeach; ?>
    </select>

    <template id="contact-field-template">
        <div class="input-group contact-field">
            <span class="input-group-text contact-label"></span>
            <input type="text" class="form-control contact-input">
            <button class="btn btn-outline-secondary remove-contact" type="button">&times;</button>
        </div>
    </template>
</div>