<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace mirocow\eav\widgets;

use mirocow\eav\handlers\AttributeHandler;
use yii\helpers\ArrayHelper;

class DropDownList extends AttributeHandler
{
    const VALUE_HANDLER_CLASS = '\mirocow\eav\handlers\OptionValueHandler';

    static $order = 24;
    
    static $view = <<<TEMPLATE
    <select>
      <% if (rf.get(Formbuilder.options.mappings.INCLUDE_BLANK)) { %> 
          <option value=''></option>  
      <% } %>
      <% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>
      <option <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'selected' %>>
      <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>
      </option>
      <% } %>
      </select>", 
      edit: "<%= Formbuilder.templates['edit/options']({ includeBlank: true }) %>    
TEMPLATE;
    
    static $edit = <<<TEMPLATE
    <%= Formbuilder.templates['edit/options']({ includeBlank: true }) %>    
TEMPLATE;
    
    static $addButton = <<<TEMPLATE
    <span class=\"symbol\"><span class=\"fa fa-caret-down\"></span></span> Dropdown    
TEMPLATE;
    
    static $defaultAttributes = <<<TEMPLATE
    function (attrs) {
            attrs.field_options.options = [
                {
                    label: "",
                    checked: false
                }, {
                    label: "",
                    checked: false
                }
            ];
            attrs.field_options.include_blank_option = false;
            return attrs;
        }    
TEMPLATE;
    

    public function init()
    {
        parent::init();

        $this->owner->addRule($this->getAttributeName(), 'in', [
            'range' => $this->getOptions(),
        ]);
    }

    public function run()
    {
        return $this->owner->activeForm->field($this->owner, $this->getAttributeName(), 
            ['template' => "{input}\n{hint}\n{error}"])
            ->dropDownList(
                ArrayHelper::map($this->attributeModel->getEavOptions()->asArray()->all(), 'id', 'value')
            );
    }
}