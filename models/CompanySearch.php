<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class CompanySearch extends Model
{
    public $id;
    public $name;
    public $contacts;
    public $comment;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'contacts', 'comment', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Company::find()->with(['vacancies.interactions', 'persons']);
        $query->joinWith(['vacancies.interactions'], true, 'Left JOIN');
        $query->groupBy('companies.id');

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
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ]);

        /*$query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'companies.comment', $this->comment]);*/

        if ($this->comment) {
            $query->andWhere(new Expression('companies.comment LIKE :comment', [
                ':comment' => '%' . $this->comment . '%'
            ]));
        }
        if ($this->name) {
            $query->andWhere(new Expression('companies.name LIKE :name OR companies.contacts LIKE :name', [
                ':name' => '%' . $this->name . '%'
            ]));
        }
        return $dataProvider;
    }
}