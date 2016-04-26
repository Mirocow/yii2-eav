<?php
use yii\db\Migration;
use yii\db\Schema;
use mirocow\eav\handlers\ValueHandler;

class m150821_133232_init extends Migration
{

    public $tables;
    public $entityName = 'eav';
    public $attributeTypes = [];
    
    public function init()
    {
        if (!$entityName = $this->entityName) {
            throw new \yii\console\Exception("Entity name must be set for EAV migration");
        }
        
        $this->tables = [
            'entity' => "{{%{$entityName}}}",
            'attribute' => "{{%{$entityName}_attribute}}",
            'attribute_type' => "{{%{$entityName}_attribute_type}}",
            'value' => "{{%{$entityName}_attribute_value}}",
            'option' => "{{%{$entityName}_attribute_option}}",
        ];
            
        $this->attributeTypes = [
            [
                'name' => 'text', 
                'storeType' => ValueHandler::STORE_TYPE_RAW, 
                'handlerClass' => '\mirocow\eav\widgets\TextInput',
            ],
            [
                'name' => 'option', 
                'storeType' => ValueHandler::STORE_TYPE_OPTION, 
                'handlerClass' => '\mirocow\eav\widgets\DropDownList',
            ],
            [
                'name' => 'checkbox', 
                'storeType' => ValueHandler::STORE_TYPE_MULTIPLE_OPTIONS, 
                'handlerClass' => '\mirocow\eav\widgets\CheckBoxList',
            ],
            [
                'name' => 'array', 
                'storeType' => ValueHandler::STORE_TYPE_ARRAY, 
                'handlerClass' => '\mirocow\eav\widgets\EncodedTextInput',
            ],
            [
                'name' => 'radio', 
                'storeType' => ValueHandler::STORE_TYPE_OPTION, 
                'handlerClass' => '\mirocow\eav\widgets\RadioList',
            ],
            [
                'name' => 'area', 
                'storeType' => ValueHandler::STORE_TYPE_RAW, 
                'handlerClass' => '\mirocow\eav\widgets\Textarea',
            ],                                                
        ];
    }

    public function safeUp()
    {
        $options = $this->db->driverName == 'mysql' 
                 ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' 
                 : null;

        $this->createTable($this->tables['entity'], [
            'id' => Schema::TYPE_PK,
            'entityName' => Schema::TYPE_STRING,
            'entityModel' => Schema::TYPE_STRING,
        ], $options);

        $this->createTable($this->tables['attribute'], [
            'id' => Schema::TYPE_PK,
            'entityId' => Schema::TYPE_INTEGER,
            'typeId' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_STRING,
            'label' => Schema::TYPE_STRING,
            'defaultValue' => Schema::TYPE_STRING,
            'defaultOptionId' => Schema::TYPE_INTEGER,
            'required' => Schema::TYPE_BOOLEAN . ' DEFAULT 1',
        ], $options);

        $this->createTable($this->tables['attribute_type'], [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING,
            'handlerClass' => Schema::TYPE_STRING,
            'storeType' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $options);

        $this->createTable($this->tables['value'], [
            'id' => Schema::TYPE_PK,
            'entityId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'attributeId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'value' => Schema::TYPE_STRING,
            'optionId' => Schema::TYPE_INTEGER,
        ], $options);

        $this->createTable($this->tables['option'], [
            'id' => Schema::TYPE_PK,
            'attributeId' => Schema::TYPE_INTEGER,
            'value' => Schema::TYPE_STRING,
        ], $options);

        if($this->db->driverName != "sqlite"){
              
            $this->addForeignKey('FK_Attribute_typeId', 
              $this->tables['attribute'], 'typeId', $this->tables['attribute_type'], 'id', "CASCADE", "NO ACTION");
              
            $this->addForeignKey('FK_EntityId', 
              $this->tables['attribute'], 'entityId', $this->tables['entity'], 'id', "CASCADE", "NO ACTION");
              
            $this->addForeignKey('FK_Value_entityId', 
              $this->tables['value'], 'entityId', $this->tables['entity'], 'id', "CASCADE", "NO ACTION");
              
            $this->addForeignKey('FK_Value_attributeId', 
              $this->tables['value'], 'attributeId', $this->tables['attribute'], 'id', "CASCADE", "NO ACTION");
              
            $this->addForeignKey('FK_Value_optionId', 
              $this->tables['value'], 'optionId', $this->tables['option'], 'id', "CASCADE", "NO ACTION");
              
            $this->addForeignKey('FK_Option_attributeId', 
              $this->tables['option'], 'attributeId', $this->tables['attribute'], 'id', "CASCADE", "NO ACTION");
        }

        foreach ($this->attributeTypes as $columns) {
            $this->insert($this->tables['attribute_type'], $columns);
        }
    }

    public function safeDown()
    {
        if($this->db->driverName != "sqlite"){
            $this->dropForeignKey('FK_Attribute_typeId', $this->tables['attribute']);
            $this->dropForeignKey('FK_EntityId', $this->tables['attribute']);
            $this->dropForeignKey('FK_Value_entityId', $this->tables['value']);
            $this->dropForeignKey('FK_Value_attributeId', $this->tables['value']);
            $this->dropForeignKey('FK_Value_optionId', $this->tables['value']);
            $this->dropForeignKey('FK_Option_attributeId', $this->tables['option']);
        }
        
        $this->dropTable($this->tables['attribute']);
        $this->dropTable($this->tables['attribute_type']);
        $this->dropTable($this->tables['value']);
        $this->dropTable($this->tables['option']);
        $this->dropTable($this->tables['entity']);
    }
    
}
