<?php

use app\helpers\Html;
use app\helpers\Icon;
use yii\grid\GridView;
use app\models\VacancySearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * @var View $this
 * @var VacancySearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'Вакансии';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    $(document).on('click', '.toggle-link', function (e) {
    e.preventDefault();

    const \$container = \$(this).closest('.text-toggle');
    const \$short = \$container.find('.short-text');
    const \$full = \$container.find('.full-text');

    if (\$full.hasClass('d-none')) {
        \$short.hide();
        \$full.removeClass('d-none');
        \$(this).text('скрыть');
    } else {
        \$full.addClass('d-none');
        \$short.show();
        \$(this).text('далее');
    }
});
");
?>
<div class="vacancy-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Icon::svg('plus'), ['create'], ['class' => 'float-end fs-1']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //'id',
            [
                'attribute' => 'title',
                'label' => 'Заголовок/Контакты',
                'format' => 'raw',
                'value' => function($model) {
                    $html = Html::a(Html::encode($model->title), Url::toRoute(['vacancy/view', 'id' => $model->id]));
                    if (!empty($model->contacts)) {
                        $html .= '<ul class="list-unstyled mb-0">';
                        foreach ($model->contacts as $type => $value) {
                            $html .= '<li><strong>' . Html::encode($type) . ':</strong> ' . Html::encode($value) . '</li>';
                        }
                        $html .= '</ul>';
                    }
                    return $html;
                }
            ],
            [
                'attribute' => 'text',
                'format' => 'raw',
                'value' => function ($model) {
                    $shortText = mb_substr($model->text, 0, 200);
                    $isTruncated = mb_strlen($model->text) > 200;
                    $html = '<div class="text-toggle">
                        <p class="short-text">' . nl2br(Html::encode($shortText)) . ($isTruncated ? '…' : '') . '</p>';
                    if ($isTruncated) {
                        $html .= '<p class="full-text d-none">' . nl2br(Html::encode($model->text)) . '</p>'
                            . '<a href="#" class="toggle-link" style="text-decoration-style: dashed;">далее</a>';
                    }
                    return $html . '</div>';
                }
            ],
            [
                'label' => 'Компания/Люди',
                'attribute' => 'company_name',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = '';
                    if ($model->company) {
                        $html .= Html::companyLink($model->company);

                        if ($model->company->persons) {
                            $html .= '<ul>';
                            foreach ($model->company->persons as $person) {
                                $html .= '<li>' . Html::personLink($person) . '</li>';
                            }
                            $html .= '</ul>';
                        }
                    }
                    return $html;
                }
            ],
            [
                'label' => 'Общение',
                'format' => 'raw',
                'attribute' => 'latestInteractionDate',
                'value' => function ($model) {
                    $person_ids = $model->company && $model->company->persons ? array_column($model->company->persons, 'id') : [];
                    $html = Html::createInteractionLink($model->id, $person_ids);
                    if ($model->interactions) {
                        $interactions = $model->interactions;
                        ArrayHelper::multisort($interactions, ['date', 'created_at'], [SORT_DESC, SORT_DESC]);
                        foreach ($interactions as $interaction) {
                            $html .= '<a href="' . Url::toRoute(['/interaction/view', 'id' => $interaction->id]) . '">'
                                . Yii::$app->formatter->asDate($interaction->date, 'php:d M Y')
                                . '</a>';
                            if ($interaction->result) {
                                $html .= ':<br />' . Html::encode($interaction->result);
                            }
                            $html .= '<br />';
                        }
                    }
                    return $html;
                }
            ],
            'comment',
            //'created_at',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>