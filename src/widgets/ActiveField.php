<?php

namespace mirocow\eav\widgets;

use mirocow\eav\helpers\Html;

class ActiveField extends \yii\widgets\ActiveField
{
    static $order = 4;  
  
    static $view = <<<TEMPLATE
    <input type='text' class='rf-size-<%= rf.get(Formbuilder.options.mappings.SIZE) %>' />    
TEMPLATE;
    
    static $edit = <<<TEMPLATE
    <%= Formbuilder.templates['edit/size']() %>
    <%= Formbuilder.templates['edit/min_max_length']() %>
TEMPLATE;
    
    static $addButton = <<<TEMPLATE
    <span class='symbol'><span class='fa fa-font'></span></span> Input field    
TEMPLATE;
    
    static $defaultAttributes = <<<TEMPLATE
function (attrs) {
            attrs.field_options.size = 'small';
            return attrs;
        }    
TEMPLATE;
  
    public function eavInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $options['form'] = $this->form;
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeEavInput($this->model, $this->attribute, $options);

        return $this;
    } 

}