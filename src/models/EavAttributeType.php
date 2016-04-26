<?php

namespace mirocow\eav\models;

use mirocow\eav\models\EavAttribute;
use Yii;

/**
 * This is the model class for table "{{%eav_attribute_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $handlerClass
 * @property integer $storeType
 *
 * @property EavAttribute[] $eavAttributes
 */
class EavAttributeType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_attribute_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['storeType'], 'integer'],
            [['name', 'handlerClass'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'handlerClass' => 'Handler Class',
            'storeType' => 'Store Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttributes()
    {
        return $this->hasMany(EavAttribute::className(), ['typeId' => 'id'])
          ->orderBy(['order' => SORT_DESC]);
    }
    
    public function getFormBuilder()
    {
      $class = $this->handlerClass;
      
      return [
        'order' => isset($class::$order)? $class::$order: 0,
        'view' => isset($class::$view)? $class::$view: 'Template view',
        'edit' => isset($class::$edit)? $class::$edit: 'Template edit',
        'addButton' => isset($class::$addButton)? $class::$addButton: 'Template button',
        'defaultAttributes' => isset($class::$defaultAttributes)? $class::$defaultAttributes: '',
      ];
      
    }
    
}