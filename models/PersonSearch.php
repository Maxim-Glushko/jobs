<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class PersonSearch extends Model
{
    public $id;
    public $name;
    public $position;
    public $contacts;
    public $comment;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'position', 'contacts', 'comment', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Person::find()->with(['interactions', 'companies.vacancies.interactions']);
        $query->joinWith(['interactions'], true, 'Left JOIN');
        $query->groupBy('persons.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'latestInteractionDate' => SORT_DESC,
                ],
                'attributes' => [
                    'name',
                    'latestInteractionDate' => [
                        'asc' => new Expression('MAX(interactions.date) ASC, MAX(interactions.created_at) ASC'),
                        'desc' => new Expression('MAX(interactions.date) DESC, MAX(interactions.created_at) DESC')
                    ]
    ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        if ($this->name) {
            $query->andWhere(new Expression('persons.contacts LIKE :name OR persons.name LIKE :name OR persons.position LIKE :name', [
                ':name' => '%' . $this->name . '%'
            ]));
        }

        return $dataProvider;
    }
}