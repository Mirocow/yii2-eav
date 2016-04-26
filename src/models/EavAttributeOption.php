<?php

namespace mirocow\eav\models;

use mirocow\eav\models\EavAttributeOption;
use mirocow\eav\models\EavAttribute;
use mirocow\eav\models\EavAttributeValue;
use Yii;

/**
 * This is the model class for table "{{%eav_attribute_option}}".
 *
 * @property integer $id
 * @property integer $attributeId
 * @property string $value
 * @property string $defaultOptionId
 *
 * @property EavAttribute[] $eavAttributes
 * @property EavAttribute $attribute
 * @property EavAttributeValue[] $eavAttributeValues
 */
class EavAttributeOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_attribute_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attributeId', 'defaultOptionId'], 'integer'],
            [['value'], 'string', 'max' => 255],
            //[['attributeId'], 'exist', 'skipOnError' => true, 'targetClass' => EavAttribute::className(), 'targetAttribute' => ['attributeId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attributeId' => 'Attribute ID',
            'value' => 'Value',
            'defaultOptionId' => 'Default option Id',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttributes()
    {
        return $this->hasMany(EavAttribute::className(), ['defaultOptionId' => 'id'])
          ->orderBy(['order' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute($name = '')
    {
        return $this->hasOne(EavAttribute::className(), ['id' => 'attributeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValues()
    {
        return $this->hasMany(EavAttributeValue::className(), ['optionId' => 'id']);
    }
}