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
        $query = Vacancy::find();
        $query->joinWith(['company'], true, 'LEFT JOIN');
        $query->select(['vacancies.*', 'companies.name as company_name']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'comment', $this->comment]);


        if ($this->contacts) {
            $query->andWhere(new Expression('contacts LIKE :contacts', [
                ':contacts' => '%' . $this->contacts . '%'
            ]));
        }

        if ($this->company_name) {
            $query->andFilterWhere(['like', 'companies.name', $this->company_name]);
        }

        return $dataProvider;
    }
}