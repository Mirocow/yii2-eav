<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace mirocow\eav\widgets;

use mirocow\eav\handlers\AttributeHandler;

class Textarea extends AttributeHandler
{
    static $order = 1;  
  
    static $view = <<<TEMPLATE
    <textarea type='text'
    rows=<%= rf.get(Formbuilder.options.mappings.AREA_ROWS) %>
    cols=<%= rf.get(Formbuilder.options.mappings.AREA_COLS) %> />
    </textarea>    
TEMPLATE;
    
    static $edit = <<<TEMPLATE
    <%= Formbuilder.templates['edit/text_area']() %>    
TEMPLATE;
    
    static $addButton = <<<TEMPLATE
    <span class='symbol'><span class='fa fa-font'></span></span> Input textarea    
TEMPLATE;
    
    static $defaultAttributes = <<<TEMPLATE
    function (attrs) {
                debugger;
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
          ->textArea();
    }
}