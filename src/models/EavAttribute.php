<?php

namespace mirocow\eav\models;

use mirocow\eav\models\EavEntity;
use mirocow\eav\models\EavAttributeValue;
use mirocow\eav\models\EavAttributeOption;
use mirocow\eav\models\EavAttributeType;
use Yii;

/**
 * This is the model class for table "{{%eav_attribute}}".
 *
 * @property integer $id
 * @property integer $entityId 
 * @property integer $typeId
 * @property string $type 
 * @property string $name
 * @property string $label
 * @property string $defaultValue
 * @property integer $defaultOptionId
 * @property integer $required
 * @property integer $order 
 * @property string $description 
 *
 * @property EavAttributeOption $defaultOption
 * @property EavAttributeType $type
 * @property EavAttributeOption[] $eavAttributeOptions
 * @property EavAttributeValue[] $eavAttributeValues
 */
class EavAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'defaultValue', 'entityModel', 'label', 'description'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 50],
            [['entityId', 'order'], 'integer'],
            //[['categoryId', 'typeId', 'defaultOptionId'], 'integer'],
            [['required'], 'boolean'],
            //[['defaultOptionId'], 'exist', 'skipOnError' => true, 'targetClass' => EavAttributeOption::className(), 'targetAttribute' => ['defaultOptionId' => 'id']],
            //[['typeId'], 'exist', 'skipOnError' => true, 'targetClass' => EavAttributeType::className(), 'targetAttribute' => ['typeId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typeId' => 'Type ID',
            'type' => 'Type',
            'name' => 'Name',
            'label' => 'Label',
            'defaultValue' => 'Default Value',
            'defaultOptionId' => 'Default Option ID',
            'required' => 'Required',
            'order' => 'Order',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefaultOption()
    {
        return $this->hasOne(EavAttributeOption::className(), ['id' => 'defaultOptionId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavType()
    {
        return $this->hasOne(EavAttributeType::className(), ['id' => 'typeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(EavEntity::className(), ['id' => 'entityId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavOptions()
    {
        return $this->hasMany(EavAttributeOption::className(), ['attributeId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttributeValues()
    {
        return $this->hasMany(EavAttributeValue::className(), ['attributeId' => 'id']);
    }
    
    public function getbootstrapData()
    {
      return [
        'cid' => '',
        'label' => '',
        'field_type' => '',
        'required' => '',
        'field_options' => [],      
      ];
    }
        
}