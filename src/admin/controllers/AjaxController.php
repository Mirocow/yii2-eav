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
class AjaxController extends Controller {

  public function actionIndex() {
    Yii::$app->response->format = 'json';

    $status = 'false';

    $attribuites = [];

    $types = EavAttributeType::find()->all();

    if ( $types ) {
      foreach ( $types as $type ) {
        $attribuites[$type->name] = $type->attributes;
        $attribuites[$type->name]['formBuilder'] = $type->formBuilder;
      }

      $status = 'success';
    }

    return ['status' => $status, 'types' => $attribuites];
  }

  public function actionSave() {

    if ( Yii::$app->request->isPost ) {

      $post = Yii::$app->request->post();

      if ( $post['payload'] && $post['entityModel'] ) {
        $payload = Json::decode($post['payload']);
        if ( !isset( $payload['fields'] ) )
          return;
        $categoryId = isset( $post['categoryId'] ) ? $post['categoryId'] : 0;
        $entityId = EavEntity::find()->select(['id'])->where(['entityModel' => $post['entityModel'], 'categoryId' => $categoryId,])->scalar();

        if ( !$entityId ) {
          $entity = new EavEntity;
          $entity->entityName = isset( $post['entityName'] ) ? $post['entityName'] : 'Untitled';
          $entity->entityModel = $post['entityModel'];
          $entity->categoryId = $categoryId;
          $entity->save(false);
          $entityId = $entity->id;
        }
        // get all attributes
        $attributesAll = EavAttribute::findAll(['entityId' => $entityId]);
        $allAttrs = [];
        foreach ( $attributesAll as $k => $v ) {
          $tmp = $v->getAttributes();
          $allAttrs[$tmp['name']] = '';
        }
        $notDeletedAttrs = [];
        // not removed attributes
        foreach ( $payload['fields'] as $k => $v ) {
          $notDeletedAttrs[$v['cid']] = '';
        }
        // remove attributes
        foreach ( $allAttrs as $k => $v ) {
          if ( !isset( $notDeletedAttrs[$k] ) ) {
            EavAttribute::deleteAll(['name' => $k]);
          }
        }
        $i = 0;
        foreach ( $payload['fields'] as $order => $field ) {
          $i++;
          $attribute = EavAttribute::findOne(['name' => $field['cid'], 'entityId' => $entityId]);
          if ( !$attribute ) {
            $attribute = new EavAttribute;
            $lastId = EavAttribute::find()->select(['id'])->orderBy(['id' => SORT_DESC])->limit(1)->scalar() + 1;
            $attribute->name = 'c' . $lastId;
          }
          $attribute->type = $field['type'];
          $attribute->entityId = $entityId;
          $attribute->typeId = EavAttributeType::find()->select(['id'])->where(['name' => $field['field_type']])->scalar();
          $attribute->label = $field['label'];
          $attribute->required = $field['required'];
          $attribute->order = $i;
          $attribute->description = $field['field_options']['description'];
          $attribute->save(false);
          if ( !isset( $field['field_options']['options'] ) )
            continue;
          // this options not removed
          foreach ( $field['field_options']['options'] as $k => $v ) {
            $notDeletedOptions[$v['id']] = '';
          }
          $optionsAll = EavAttributeOption::findAll(['attributeId' => $attribute->id]);
          foreach ( $optionsAll as $k => $v ) {
            $optionsAllArr[$v->id] = '';
            if ( !isset($notDeletedOptions[$v->id]) ) {
              // remove option
              EavAttributeOption::deleteAll(['id' => $v->id]);
            }
          }
          foreach ( $field['field_options']['options'] as $k => $v ) {
            if ( !isset($optionsAllArr[$v['id']]) ) {
              // add new option
              $option = new EavAttributeOption;
              $option->value = $v['label'];
              $option->attributeId = $attribute->id;
              //$option->order = $k;
              $option->save();
            }
          }
        }
      }
      return true;
    }
  }
}