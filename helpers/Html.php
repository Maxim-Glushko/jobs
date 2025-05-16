<?php

namespace app\helpers;

use app\models\Company;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use app\models\Vacancy;
use app\models\Person;


class Html extends \yii\bootstrap5\Html
{
    public static function personLink(Person $person): string
    {
        return Html::a(Html::encode($person->name), Url::toRoute(['person/view', 'id' => $person->id]));
    }

    public static function vacancyLink(Vacancy $vacancy): string
    {
        return Html::a(Html::encode($vacancy->title), Url::toRoute(['vacancy/view', 'id' => $vacancy->id]));
    }

    public static function companyLink(Company $company): string
    {
        return Html::a(Html::encode($company->name), Url::toRoute(['company/view', 'id' => $company->id]));
    }

    public static function createVacancyLink(int $company_id): string
    {
        return Html::a(Icon::svg('plus'), Url::toRoute(['vacancy/create', 'company_id' => $company_id]), [
            'title' => 'добавить вакансию',
            'class' => 'float-end',
            'target' => '_blank'
        ]);
    }

    public static function createPersonLink(int $company_id): string
    { // пока не вижу ни одной причины сразу пихать массив на вход функции
        return Html::a(Icon::svg('plus'), Url::toRoute(['person/create', 'company_ids' => [$company_id]]), [
            'title' => 'добавить персону',
            'class' => 'float-end',
            'target' => '_blank'
        ]);
    }

    public static function createInteractionLink(int $vacancy_id, array $person_ids = []): string
    {
        return Html::a(Icon::svg('plus'), Url::toRoute(['interaction/create', 'vacancy_id' => $vacancy_id, 'person_ids' => $person_ids]), [
            'title' => 'добавить общение',
            'class' => 'float-end',
            'target' => '_blank'
        ]);
    }

    /**
     * @param Company|Vacancy|Person $model
     * @return string
     */
    public static function contactsList(ActiveRecord $model): string
    {
        $html = '';
        if (!empty($model->contacts)) {
            $html .= '<ul class="list-unstyled mb-0">';
            foreach ($model->contacts as $type => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $html .= '<li><strong>' . Html::encode($type) . ':</strong> ' . Html::encode($v) . '</li>';
                    }
                } else {
                    $html .= '<li><strong>' . Html::encode($type) . ':</strong> ' . Html::encode($value) . '</li>';
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }
}