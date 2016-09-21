<?php

namespace app\components\showMessage;

use yii\web\AssetBundle;

class ShowMessageAsset extends AssetBundle
{
    public $sourcePath = '@app/components/showMessage';
    public $css = [
        'css/showMessage.css',
    ];
    public $js = [
        'js/showMessage.js',
    ];
    public $publishOptions = [
        'except' => [
            'scss',
            'views',
            '*.php',
            'css/showMessage.css.map',
        ],
    ];
}