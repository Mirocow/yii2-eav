<?php

namespace mirocow\eav\models;

use Yii;

/**
 * This is the model class for table "{{%eav_entity}}".
 *
 * @property integer $id
 * @property string $entityName
 * @property string $entityModel 
 *
 * @property EavAttribute[] $eavAttributes
 */
class EavEntity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_entity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entityModel', 'entityName'], 'string', 'max' => 100],
            [['categoryId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entityName' => 'Name',
            'entityModel' => 'Entity Model',
            'categoryId' => 'ID Category',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttributes()
    {
        return $this->hasMany(EavAttribute::className(), ['entityId' => 'id'])
          ->orderBy(['order' => SORT_DESC]);
    }
}