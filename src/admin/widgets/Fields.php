<?php

namespace mirocow\eav\admin\widgets;

use mirocow\eav\models\EavAttribute;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class Fields extends Widget
{
    public $url = ['eav/admin/ajax/index'];
    
    public $urlSave = ['eav/admin/ajax/save'];
    
    public $model;
    
    public $categoryId;
    
    public $entityModel;
    
    public $entityName = 'Untitled';
    
    public $options = [];
    
    private $bootstrapData = [];
    
    public function init()
    {
        parent::init();
        
        $this->url = Url::to($this->url);
        
        $this->urlSave = Url::to($this->urlSave);
        
        $this->entityModel = str_replace('\\', '\\\\', $this->entityModel);
        
        foreach($this->model->getEavAttributes()->all() as $attr) {
          
            $options = [
              'description' => $attr->description,
            ];
            
            foreach($attr->eavOptions as $option){
              $options['options'][] = [
                'label' => $option->value,
                'id' => $option->id,
                'checked' => (bool) $option->defaultOptionId,
              ];
            }
          
            $this->bootstrapData[] = [
              'type' => $attr->type,
              'label' => $attr->label,
              'field_type' => $attr->eavType->name,
              'required' => $attr->required,
              'field_options' => $options,
              'cid' => $attr->name,
            ];
           
        }
        
        $this->bootstrapData = Json::encode($this->bootstrapData);
    }

    public function run()
    {
        return $this->render('fields', [
          'url' => $this->url,
          'urlSave' => $this->urlSave,
          'options' => $this->options,
          'id' => $this->model->id,
          'categoryId' => $this->categoryId,
          'entityModel' => $this->entityModel,
          'entityName' => $this->entityName,
          'bootstrapData' => $this->bootstrapData,
        ]);
    }
}