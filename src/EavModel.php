<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace mirocow\eav;

use mirocow\eav\handlers\AttributeHandler;
use mirocow\eav\handlers\ArrayValueHandler;
use mirocow\eav\handlers\ValueHandler;

use Yii;
use yii\base\DynamicModel as BaseEavModel;
use yii\db\ActiveRecord;
use yii\widgets\ActiveForm;

/**
 * Class EavModel
 * @package mirocow\eav
 */
class EavModel extends BaseEavModel
{
    /** @var string Class to use for storing data */
    public $valueClass;
    /** @var ActiveRecord */
    public $entityModel;
    /** @var AttributeHandler[] */
    public $handlers;
    /** @var string */
    public $attribute = '';    
    /** @var ActiveForm */
    public $activeForm;
    /** @var string[] */
    private $attributeLabels = [];

    /**
     * Constructor for creating form model from entity object
     *
     * @param array $params
     * @return static
     */
    public static function create($params)
    {
        $params['class'] = static::className();
        
        /** @var static $model */
        $model = Yii::createObject($params);
        
        $params = [];
        
        if(!empty($params['attribute'])){
          $params = ['name' => $params['attribute']];
        }
        
        $attributes = $model
          ->entityModel
          ->getRelation('eavAttributes')
          ->where($params)
          ->all();                             
          
        foreach ($attributes as $attribute) {          
        
            $handler = AttributeHandler::load($model, $attribute);
            $key = $handler->getAttributeName();
            $value = $handler->valueHandler->load();
            $model->setLabel($key, $handler->getAttributeLabel());
            
            //
            // Add rules
            //
            
            if ($attribute->required){
                $model->addRule($key, 'required');
            } else {
                $model->addRule($key, 'safe');
            }

            if ($attribute->eavType->storeType == ValueHandler::STORE_TYPE_RAW){
                $model->addRule($key, 'default', ['value' => $attribute->defaultValue]);
            }

            if ($attribute->eavType->storeType == ValueHandler::STORE_TYPE_OPTION){
                $model->addRule($key, 'default', ['value' => $attribute->defaultOptionId]);
            }
            
            if ($attribute->eavType->storeType == ValueHandler::STORE_TYPE_ARRAY){
                $model->addRule($key, 'string');
            }

            //
            // Add define attribute
            //
            
            $model->defineAttribute($key, $value);

            //
            // Add hanler
            //
            
            $model->handlers[$key] = $handler;
        
        }
        
        if(Yii::$app->request->isPost && Yii::$app->request->getIsConsoleRequest() == false)
        {
          $modelName = substr(strrchr(self::className(), "\\"), 1);
          $model->load(Yii::$app->request->post(), $modelName);         
        }                

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function getAttributeLabels()
    {
        return $this->attributeLabels;
    }

    public function setLabel($name, $label)
    {
        $this->attributeLabels[$name] = $label;
    }

    public function save($runValidation = true, $attributes = null)
    {
        if(!$this->handlers){
          Yii::info('Dynamic model data were no attributes.', __METHOD__);
          return false;
        }
        
        if ($runValidation && !$this->validate($attributes)) {
            Yii::info('Dynamic model data were not save due to validation error.', __METHOD__);
            return false;
        }

        $db = $this->entityModel->getDb();

        $transaction = $db->beginTransaction();
        try {
            foreach ($this->handlers as $handler) {
                $handler->valueHandler->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    
    public function __set($name, $value)
    {
      $this->defineAttribute($name, $value);      
    }
    
    public function getValue(){
      
      if(isset($this->attributes[ $this->attribute ])){
        return $this->attributes[ $this->attribute ];
      } else {
        return '';
      }
      
    }
    
    public function __toString()
    {
      if(isset($this->attributes[ $this->attribute ])){
        if(is_string($this->attributes[ $this->attribute ])){
          return (string) $this->attributes[ $this->attribute ];
        } else {
          return (string) json_encode($this->attributes[ $this->attribute ]);
        }
      } else {
        return '';
      }
    }
        
}