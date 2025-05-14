<?php

namespace app\models;

use app\models\traits\JsonValidator;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\db\ActiveQuery;

/**
 * Class Company
 * @package app\models
 *
 * @property int $id ID компании
 * @property string $name Название компании
 * @property array $contacts Контактные данные в JSON формате
 * @property string $comment Комментарий
 * @property string $created_at Дата создания записи
 * @property string $updated_at Дата последнего обновления
 *
 * @property Vacancy[] $vacancies Связанные вакансии
 * @property Person[] $persons
 */
class Company extends ActiveRecord
{
    use JsonValidator;

    public static function tableName(): string
    {
        return '{{%companies}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => AttributeTypecastBehavior::class,
                'attributeTypes' => [
                    'contacts' => function ($value) {
                        if (empty($value)) {
                            return [];
                        }
                        return is_string($value) ? Json::decode($value) : $value;
                    }
                ],
                'typecastAfterValidate' => true,
                'typecastBeforeSave' => true,
                'typecastAfterFind' => true,
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'comment'], 'trim'],
            [['name', 'comment'], 'string'],
            ['contacts', 'validateJson'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'contacts' => 'Контакты',
            'comment' => 'Комментарий',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function forSelect(): array
    {
        return ArrayHelper::map(static::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }

    public function getVacancies(): ActiveQuery
    {
        return $this->hasMany(Vacancy::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Persons]]
     * @throws InvalidConfigException
     */
    public function getPersons(): ActiveQuery
    {
        return $this->hasMany(Person::class, ['id' => 'person_id'])
            ->viaTable('{{%persons_companies}}', ['company_id' => 'id']);
    }
}