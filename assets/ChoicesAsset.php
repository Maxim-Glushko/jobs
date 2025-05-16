<?php
namespace app\assets;

use yii\web\AssetBundle;

class ChoicesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/choices.min.css',
    ];

    public $js = [
        'js/choices.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
