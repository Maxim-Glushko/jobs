<?php

namespace app\models;

use app\models\traits\JsonValidator;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\db\ActiveQuery;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "persons".
 *
 * @property int $id
 * @property string $name
 * @property string|null $position
 * @property string|null $contacts
 * @property string|null $comment
 * @property string $created_at
 * @property string $updated_at
 * @property array $company_ids
 *
 * @property Company[] $companies
 * @property Interaction[] $interactions
 */
class Person extends ActiveRecord
{
    use JsonValidator;

    public array $company_ids = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%persons}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ], [
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
            [['name'], 'required'],
            [['name', 'position', 'comment'], 'string'],
            ['contacts', 'validateJson'],
            ['company_ids', 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => 'id']]
        ];
    }

    public function beforeValidate()
    {
        if (is_array($this->company_ids)) {
            $this->company_ids = array_values(array_unique(array_filter($this->company_ids)));
        }
        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'position' => 'Должность',
            'contacts' => 'Контакты',
            'comment' => 'Комментарий',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Companies]]
     * @throws InvalidConfigException
     */
    public function getCompanies(): ActiveQuery
    {
        return $this->hasMany(Company::class, ['id' => 'company_id'])
            ->viaTable('{{%persons_companies}}', ['person_id' => 'id']);
    }

    /**
     * Gets query for [[Interactions]]
     * @throws InvalidConfigException
     */
    public function getInteractions(): ActiveQuery
    {
        return $this->hasMany(Interaction::class, ['id' => 'interaction_id'])
            ->viaTable('{{%persons_interactions}}', ['person_id' => 'id']);
    }

    public function updateCompanies(array $companyIds)
    {
        $currentCompanyIds = $this->getCompanies()->select('id')->column();

        $toAdd = array_diff($companyIds, $currentCompanyIds);
        $toRemove = array_diff($currentCompanyIds, $companyIds);

        foreach ($toAdd as $companyId) {
            $company = Company::findOne($companyId);
            if ($company) {
                $this->link('companies', $company);
            }
        }

        foreach ($toRemove as $companyId) {
            $company = Company::findOne($companyId);
            if ($company) {
                $this->unlink('companies', $company, true);
            }
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->updateCompanies($this->company_ids);
    }

    public static function forSelect(): array
    {
        return ArrayHelper::map(static::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }
}