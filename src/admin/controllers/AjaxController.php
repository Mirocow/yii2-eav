<?php

namespace mirocow\eav\admin\controllers;

use mirocow\eav\models\EavAttribute;
use mirocow\eav\models\EavAttributeType;
use mirocow\eav\models\EavAttributeSearch;
use mirocow\eav\models\EavAttributeOption;
use mirocow\eav\models\EavEntity;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * AttributeController implements the CRUD actions for EavAttribute model.
 */
class AjaxController extends Controller
{
  
  public function actionIndex()
  {
    Yii::$app->response->format = 'json';
    
    $status = 'false';
    
    $attribuites = [];
    
    $types = EavAttributeType::find()->all();
    
    if($types){
      foreach($types as $type){
        $attribuites[$type->name] = $type->attributes;
        $attribuites[$type->name]['formBuilder'] = $type->formBuilder;
      }
      
      $status = 'success';
    }
    
    return ['status' => $status, 'types' => $attribuites];
  }
  
  public function actionSave()
  {
     if(Yii::$app->request->isPost){
       
       $post = Yii::$app->request->post();
       
       if($post['payload'] && $post['entityModel']){
         
         $payload = Json::decode($post['payload']);
         
         if(!isset($payload['fields'])) return;
         
         $categoryId = isset($post['categoryId'])? $post['categoryId']: 0;
         $entityId = EavEntity::find()
          ->select(['id'])
          ->where([
            'entityModel' => $post['entityModel'],
            'categoryId' => $categoryId,
          ])->scalar();
                    
         if(!$entityId){
           $entity = new EavEntity;
           $entity->entityName = isset($post['entityName'])? $post['entityName']: 'Untitled';
           $entity->entityModel = $post['entityModel'];
           $entity->categoryId = $categoryId;
           $entity->save(false);
           
           $entityId = $entity->id;
         }
         
         foreach($payload['fields'] as $order => $field){
           
           $attribute = EavAttribute::findOne(['name' => $field['cid'], 'entityId' => $entityId]);
           if(!$attribute){
              $attribute = new EavAttribute;
              $lastId = EavAttribute::find()->select(['id'])->orderBy(['id' => SORT_DESC])->limit(1)->scalar() + 1;
              $attribute->name = 'c'.$lastId;              
           }
           
           //$attribute->name = $field['cid'];
           $attribute->type = $field['type'];
           $attribute->entityId = $entityId;
           $attribute->typeId = EavAttributeType::find()->select(['id'])->where(['name' => $field['field_type']])->scalar();            
           $attribute->label = $field['label'];
           //$attribute->defaultValue = '';
           //$attribute->defaultOptionId = 0;
           $attribute->required = $field['required'];
           $attribute->order = $order;
           $attribute->description = isset($field['field_options']['description'])? $field['field_options']['description']: '';
           $attribute->save(false);
                      
           if(!isset($field['field_options']['options'])) continue;
             
           EavAttributeOption::deleteAll(['attributeId' => $attribute->id]);
           
           foreach($field['field_options']['options'] as $o){
              $option = new EavAttributeOption;
              $option->attributeId = $attribute->id;
              $option->value = $o['label'];
              $option->defaultOptionId = (int) $o['checked'];
              $option->save();
           }
           
         }
         
       }
       
     }
  }
  
}  