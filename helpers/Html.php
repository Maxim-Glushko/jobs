<?php

namespace app\helpers;

use app\models\Company;
use yii\helpers\Url;
use app\models\Vacancy;
use app\models\Person;


class Html extends \yii\bootstrap5\Html
{
    public static function personLink(Person $person): string
    {
        return '<a href="' . Url::toRoute(['/person/view', 'id' => $person->id]) . '">' . Html::encode($person->name) . '</a>';
    }

    public static function vacancyLink(Vacancy $vacancy): string
    {
        return '<a href="' . Url::toRoute(['/vacancy/view', 'id' => $vacancy->id]) . '">' . Html::encode($vacancy->title) . '</a>';
    }

    public static function companyLink(Company $company): string
    {
        return '<a href="' . Url::toRoute(['company/view', 'id' => $company->id]) . '">' . Html::encode($company->name) . '</a>';
    }
}