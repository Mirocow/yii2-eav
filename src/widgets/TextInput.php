<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace mirocow\eav\widgets;

use mirocow\eav\handlers\AttributeHandler;

class TextInput extends AttributeHandler
{
    static $order = 0;
  
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
    
    
    public function init()
    {
        parent::init();
        
        $this->owner->addRule($this->getAttributeName(), 'string', ['max' => 255]);
    }

    public function run()
    {
        return $this->owner->activeForm
          ->field($this->owner, $this->getAttributeName(), ['template' => "{input}\n{hint}\n{error}"])
          ->textInput();
    }
}