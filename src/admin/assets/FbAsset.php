<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace mirocow\eav\admin\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FbAsset extends AssetBundle
{
  
    //public $basePath = '@mirocow/eav/admin/assets/formbuilder';
    public $baseUrl = '@web';
    public $sourcePath = '@mirocow/eav/admin/assets/formbuilder';

    public $css = [
        'css/vendor.css',
        'css/formbuilder.css',
    ];
    
    public $js = [
        'js/vendor.js',
        'js/formbuilder.js',
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];
  
}