<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * VacancySearch represents the model behind the search form of `app\models\Vacancy`.
 */
class VacancySearch extends Vacancy
{
    public $id;
    public ?string $company_name = null;
    public $title;
    public $text;
    public $comment;
    public $contacts;
    public $created_at;
    public $updated_at;

    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['title', 'text', 'comment', 'created_at', 'updated_at', 'company_name', 'contacts'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Vacancy::find()->with(['company.persons', 'interactions.persons']); // по кругу, шо капец, но по-иному никак
        $query->joinWith(['company'], true, 'LEFT JOIN');
        $query->joinWith(['interactions'], true, 'LEFT JOIN');
        $query->select(['vacancies.*', 'companies.name as company_name']);
        $query->groupBy('vacancies.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'latestInteractionDate' => SORT_DESC,
                ],
                'attributes' => [
                    'title',
                    'comment',
                    'latestInteractionDate' => [
                        'asc' => new Expression('MAX(interactions.date) ASC, MAX(interactions.created_at) ASC'),
                        'desc' => new Expression('MAX(interactions.date) DESC, MAX(interactions.created_at) DESC'),
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

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        if ($this->title) {
            $query->andWhere(new Expression('vacancies.contacts LIKE :title OR vacancies.title LIKE :title', [
                ':title' => '%' . $this->title . '%'
            ]));
        }

        if ($this->company_name) {
            $query->andFilterWhere(['like', 'companies.name', $this->company_name]);
        }

        return $dataProvider;
    }
}