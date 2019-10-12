<?php

namespace app\modules\admin\assets\movie;

use yii\jui\JuiAsset;
use yii\web\AssetBundle;

class MovieAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/views/movie/assets';

    public $js = [
        'movie.js',
    ];

    public $depends = [
        JuiAsset::class,
    ];
}