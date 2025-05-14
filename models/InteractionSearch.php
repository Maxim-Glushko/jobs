<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\base\Model;
use yii\db\Expression;

class InteractionSearch extends Interaction
{
    public ?string $company_name = null;
    public $vacancy_id;
    public ?string $vacancy_title = null;
    public ?string $person_name = null;
    public $text;
    public $result;
    public $persons;
    public $date;

    public function rules(): array
    {
        return [
            [['id', 'vacancy_id'], 'integer'],
            [['text', 'result', 'created_at', 'updated_at'], 'safe'],
            [['company_name', 'vacancy_title', 'person_name'], 'string'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search($params): ActiveDataProvider
    {
        $query = Interaction::find()->select(['interactions.*']);

        $query->joinWith(['vacancy'], true, 'LEFT JOIN');
        $query->addSelect(['vacancies.title as vacancy_title']);

        $query->joinWith(['vacancy.company'], true, 'LEFT JOIN');
        $query->addSelect(['companies.name as company_name']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'created_at' => [
                        'asc' => ['interactions.created_at' => SORT_ASC],
                        'desc' => ['interactions.created_at' => SORT_DESC],
                    ],
                    'company_name' => [
                        'asc' => ['companies.name' => SORT_ASC],
                        'desc' => ['companies.name' => SORT_DESC],
                    ],
                    'vacancy_title' => [
                        'asc' => ['vacancies.title' => SORT_ASC],
                        'desc' => ['vacancies.title' => SORT_DESC],
                    ],
                    'date' => [
                        'asc' => new Expression('interactions.date ASC, interactions.updated_at ASC'),
                        'desc' => new Expression('interactions.date DESC, interactions.updated_at DESC'),
                    ]
                ],
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vacancy_id' => $this->vacancy_id,
        ]);

        $query->andFilterWhere(['like', 'interactions.text', $this->text])
            ->andFilterWhere(['like', 'interactions.result', $this->result]);

        if (!empty($this->company_name)) {
            $query->andFilterWhere(['like', 'companies.name', $this->company_name]);
        }
        if (!empty($this->vacancy_title)) {
            $query->andFilterWhere(['like', 'vacancies.title', $this->vacancy_title]);
        }

        if (!empty($this->person_name)) {
            $query->joinWith(['persons'], true, 'LEFT JOIN');
            $query->addSelect(['persons.name as person_name']);
        }
        if (!empty($this->person_name)) {
            $query->andFilterWhere(['like', 'persons.name', $this->person_name]);
        }

        return $dataProvider;
    }
}