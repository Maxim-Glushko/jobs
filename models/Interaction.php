<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "interactions".
 *
 * @property int $id
 * @property string|null $text
 * @property string|null $result
 * @property int|null $vacancy_id
 * @property ?string $vacancy_title
 * @property ?string $company_name
 * @property string $date
 * @property string $created_at
 * @property string $updated_at
 * @property array|string|null $person_ids
 *
 * @property Vacancy $vacancy
 * @property Person[] $persons
 */
class Interaction extends ActiveRecord
{
    public ?string $company_name = null;
    public ?string $vacancy_title = null;
    public ?string $person_name = null;

    /** @var array|string|null $person_ids */
    public $person_ids = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%interactions}}';
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['text', 'result'], 'string'],
            [['text', 'result'], 'trim'/*, 'chars' => " \t\n\r\0\x0B\xC2\xA0"*/],
            [['vacancy_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['vacancy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vacancy::class, 'targetAttribute' => ['vacancy_id' => 'id']],
            ['person_ids', 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => 'id']]
        ];
    }

    public function beforeValidate()
    {
        if (is_array($this->person_ids)) {
            $this->person_ids = array_values(array_unique(array_filter($this->person_ids)));
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
            'text' => 'Описание',
            'result' => 'Результат',
            'vacancy_id' => 'Вакансия',
            'date' => 'Дата общения',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Vacancy]]
     */
    public function getVacancy(): ActiveQuery
    {
        return $this->hasOne(Vacancy::class, ['id' => 'vacancy_id']);
    }

    /**
     * Gets query for [[Persons]]
     * @throws InvalidConfigException
     */
    public function getPersons(): ActiveQuery
    {
        return $this->hasMany(Person::class, ['id' => 'person_id'])
            ->viaTable('{{%persons_interactions}}', ['interaction_id' => 'id']);
    }

    public function updatePersons(array $personIds)
    {
        $currentPersonIds = $this->getPersons()->select('id')->column();

        $toAdd = array_diff($personIds, $currentPersonIds);
        $toRemove = array_diff($currentPersonIds, $personIds);

        foreach ($toAdd as $personId) {
            $person = Person::findOne($personId);
            if ($person) {
                $this->link('persons', $person);
            }
        }

        foreach ($toRemove as $personId) {
            $person = Person::findOne($personId);
            if ($person) {
                $this->unlink('companies', $person, true);
            }
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->updatePersons(empty($this->person_ids) ? [] : $this->person_ids);
    }
}