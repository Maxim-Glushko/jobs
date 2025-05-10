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
        $query = Person::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        // Добавляем поиск по JSON-полю contacts
        if ($this->contacts) {
            $query->andWhere(new Expression('contacts LIKE :contacts', [
                ':contacts' => '%' . $this->contacts . '%'
            ]));
        }

        return $dataProvider;
    }
}