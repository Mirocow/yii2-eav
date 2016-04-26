<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace mirocow\eav\widgets;

use mirocow\eav\handlers\AttributeHandler;
use yii\helpers\ArrayHelper;

class RadioList extends AttributeHandler
{
    const VALUE_HANDLER_CLASS = '\mirocow\eav\handlers\OptionValueHandler';

    static $order = 15;    
    
    static $view = <<<TEMPLATE
    <% for (i in (rf.get(Formbuilder.options.mappings.OPTIONS) || [])) { %>
    <div>
    <label class='fb-option'>
    <input type='radio' <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].checked && 'checked' %> onclick=\"javascript: return false;\" />
    <%= rf.get(Formbuilder.options.mappings.OPTIONS)[i].label %>
    </label>
    </div>
    <% } %>
    <% if (rf.get(Formbuilder.options.mappings.INCLUDE_OTHER)) { %>
    <div class='other-option'>
    <label class='fb-option'>
    <input type='radio' />
    Other</label>
    <input type='text' />
    </div>
    <% } %>    
TEMPLATE;
    
    static $edit = <<<TEMPLATE
    <%= Formbuilder.templates['edit/options']({ includeOther: true }) %>    
TEMPLATE;
    
    static $addButton = <<<TEMPLATE
    <span class=\"symbol\"><span class=\"fa fa-circle-o\"></span></span> Radio    
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
            ->radioList(
                ArrayHelper::map($this->attributeModel->getEavOptions()->asArray()->all(), 'id', 'value')
            );
    }
}