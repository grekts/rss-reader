<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $depends = [
        'app\components\sendUrlForm\SendUrlFormAsset',
    ];
}

