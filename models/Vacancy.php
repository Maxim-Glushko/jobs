<?php

namespace app\models;

use app\models\traits\JsonValidator;
use app\models\Company;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "vacancies".
 *
 * @property int $id
 * @property int $company_id
 * @property string $title
 * @property string|null $text
 * @property array|null $contacts
 * @property string|null $comment
 * @property string $interview_date
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Company $company
 * @property Interaction[] $interactions
 */
class Vacancy extends ActiveRecord
{
    public ?string $company_name = null;

    use JsonValidator;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%vacancies}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('CURRENT_TIMESTAMP'),
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

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title', 'text', 'comment', 'company_name'], 'string'],
            [['title', 'text', 'comment', 'company_name'], 'trim'],
            [['company_id'], 'integer'],
            ['contacts', 'validateJson'],
            [['interview_date'], 'date', 'format' => 'php:Y-m-d'],
            [['created_at', 'updated_at'], 'safe'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'company_id' => 'Компания',
            'title' => 'Заголовок',
            'text' => 'Описание',
            'contacts' => 'Контакты',
            'comment' => 'Комментарий',
            'interview_date' => 'Собес.',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    public function getInteractions(): ActiveQuery
    {
        return $this->hasMany(Interaction::class, ['vacancy_id' => 'id']);
    }

    public static function forSelect(): array
    {
        return ArrayHelper::map(
            static::find()
                ->select(['CONCAT(vacancies.title, " (", companies.name, ")") as title', 'vacancies.id'])
                ->leftJoin('companies', 'companies.id = vacancies.company_id')
                ->indexBy('id')
                ->orderBy(['title' => SORT_ASC])
                ->all(),
            'id', 'title'
        );
    }
}