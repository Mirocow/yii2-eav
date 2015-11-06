Yii 2.0 EAV
===========

This extension is an implementation of Entity-Atrribute-Value pattern that could be used to get dynaminc attributes within your model.

[![Latest Stable Version](https://poser.pugx.org/mirocow/yii2-eav/v/stable)](https://packagist.org/packages/mirocow/yii2-eav) [![Latest Unstable Version](https://poser.pugx.org/mirocow/yii2-eav/v/unstable)](https://packagist.org/packages/mirocow/yii2-eav) [![Total Downloads](https://poser.pugx.org/mirocow/yii2-eav/downloads)](https://packagist.org/packages/mirocow/yii2-eav) [![License](https://poser.pugx.org/mirocow/yii2-eav/license)](https://packagist.org/packages/mirocow/yii2-eav)

## Overview of EAV

![](https://leto37g.storage.yandex.net/rdisk/39e002d318fd33be41970b967b4303c37a1f4b16403d63682ab886d651ce802a/inf/Hog8_iKY1Wf6sUzNXwwgMSF5sTcPdaFgbR-Kev1KBV5sArQFlpqATaNEgJIrClgwB4eC4zSs9Zb6gn5qKFu2og==?uid=0&filename=2015-10-20%2019-27-25%20Update%20Product%20%D0%93%D0%B5%D0%BD%D0%B5%D1%80%D0%B0%D1%82%D0%BE%D1%80%201%20-%20Google%20Chrome.png&disposition=inline&hash=&limit=0&content_type=image%2Fpng&tknv=v2&rtoken=59fdb448c386267c139e95574b212b70&force_default=no&ycrid=na-867e638af3256a70a221ab782049b1dc-downloader5g)

![](https://leto26g.storage.yandex.net/rdisk/c1e8a4e578fc7eb81f15e55b3b701b579a956f3f50b970317a45be77ea74e29e/inf/GXjb0Acw_fJH0kKchSpN6X8lFiJUhZLz2crK1-A3jya-ivvSMVWOB5sfgVJI3Dx8XCMkbuSuHpDJv9TmLFnssA==?uid=0&filename=2015-10-21%2017-32-33%20SQLyog%20Ultimate%20-%20%5Bdebian7.loc%20jiajiayoupin_loc%20-%20root%40localhost%20-%20Using%20SSH%20tunnel%20to%20debian7.loc%20%5D.png&disposition=inline&hash=&limit=0&content_type=image%2Fpng&tknv=v2&rtoken=c8232271c6f3b63bd82fe2d7bfd172c5&force_default=no&ycrid=na-d3258093d3bed98ec93d783e2d9412ab-downloader6g)

## Installation

The extension could be installed via Composer:

```
composer require --prefer-dist "mirocow/yii2-eav"
```

## Configuration

You need to apply migrations before using an extension:

``` sh
php yii migrate/up --migrationPath=@mirocow/eav/migrations
```

## Usage

First of all you need to modify your model:

``` php
class Product extends \yii\db\ActiveRecord
{

    use EavTrait;
    
    /**
     * create_time, update_time to now()
     * crate_user_id, update_user_id to current login user id
     */
    public function behaviors()
    {
        return [
            'eav' => [
                'class' => \mirocow\eav\EavBehavior::className(),
                // model for object_attribute_value table
                'valueClass' => \mirocow\eav\models\EavAttributeValue::className(),
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttributes()
    {       
        return \mirocow\eav\models\EavAttribute::find()
          ->joinWith('entity')
          ->where([
            'categoryId' => isset()? $this->category->id: 0,
            'entityModel' => $this::className()
        ]);  
    }
    
}
```

Then use the following in a view to load model field:

``` html
<?=$form->field($model,'test5', ['class' => '\mirocow\eav\widgets\ActiveField'])->eavInput(); ?>
```

To load all fields use the following:

``` html
<?php
foreach($model->getEavAttributes()->all() as $attr){
    echo $form->field($model, $attr->name, ['class' => '\mirocow\eav\widgets\ActiveField'])->eavInput();
}        
?>
```

In order to get all attributes:

```php
<p>
Encoded:

<?php
  foreach($model->getEavAttributes()->all() as $attr){
    print_r($model[$attr->name]['value']);
  }
?>
</p> 

<p>
String:

<?php
  foreach($model->getEavAttributes()->all() as $attr){
    echo $model[$attr->name];
  }
?>
```

In order to add or edit attribute:

``` html
<?= mirocow\eav\admin\widgets\Fields::widget([
    'model' => $model,
    'categoryId' => $model->id,
    'entityName' => 'Продукт',
    'entityModel' => 'common\models\Product',
])?>
```

### Admin UI

![](https://leto11e.storage.yandex.net/rdisk/ea7a2991d3fe6c8bbcd3e0dcad7465a0e27c6873d849018c4a97045c106af450/inf/I2_HSvy0rl4zZnRWw23cDzVCCdCGCscflHsesyE_019vyeFyKxP5r-9ZqUgmd7CxPrZeKYt1aF9KOBNwHDqKLw==?uid=0&filename=2015-10-23%2017-21-51%20admin.jiajiayoupin.loc%20eav%20-%20Google%20Chrome.png&disposition=inline&hash=&limit=0&content_type=image%2Fpng&tknv=v2&rtoken=c8232271c6f3b63bd82fe2d7bfd172c5&force_default=no&ycrid=na-d57c811c7608fb4f1b727503f1204614-downloader9g)
