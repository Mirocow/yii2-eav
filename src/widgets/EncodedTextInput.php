<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace mirocow\eav\widgets;

use mirocow\eav\handlers\AttributeHandler;

class EncodedTextInput extends TextInput
{
    const VALUE_HANDLER_CLASS = '\mirocow\eav\handlers\ArrayValueHandler';

    static $order = 3;

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
    <span class='symbol'><span class='fa fa-paragraph'></span></span> Input textarea    
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
        AttributeHandler::init();
    }

    public function run()
    {
        return $this->owner->activeForm
            ->field($this->owner, $this->getAttributeName(), ['template' => "{input}\n{hint}\n{error}"])
            ->textArea();
    }
}