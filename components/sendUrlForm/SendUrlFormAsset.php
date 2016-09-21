<?php

namespace app\components\sendUrlForm;

use yii\web\AssetBundle;

class SendUrlFormAsset extends AssetBundle
{
    public $sourcePath = '@app/components/sendUrlForm';
    public $css = [
        'css/sendUrlForm.css',
    ];
    public $js = [
        'js/sendUrlForm.js',
    ];
    public $publishOptions = [
        'except' => [
            'scss',
            'views',
            '*.php',
        ],
    ];
    public $depends = [
        'app\components\showMessage\ShowMessageAsset',
    ];
}

