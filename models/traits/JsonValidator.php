<?php

namespace app\models\traits;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\helpers\Json;

/**
 * Trait JsonValidator
 * @package app\traits
 *
 * @mixin Model
 */
trait JsonValidator
{
    /**
     * @param $attribute
     * @return void
     * @used-by rules()
     */
    public function validateJson($attribute): void
    {
        if (empty($this->$attribute)) {
            return;
        }

        try {
            if (is_string($this->$attribute)) {
                Json::decode($this->$attribute);
            }
        } catch (InvalidArgumentException $e) {
            $this->addError($attribute, 'Неверный формат JSON');
        }
    }
}