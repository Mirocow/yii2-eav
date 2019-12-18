<?php

use yii\db\Migration;

class m160711_132535_delete_foregin_key_from_values extends Migration
{
    public function up()
    {
        $this->dropForeignKey(
            'FK_Value_optionId',
            '{{%eav_attribute_value}}'
        );
        
        $this->dropForeignKey(
            'FK_Value_entityId',
            '{{%eav_attribute_value}}'
        );
    }

    public function down()
    {
        $this->addForeignKey('FK_Value_optionId',
                '{{%eav_attribute_value}}', 'optionId', '{{%eav_attribute_option}}', 'id', "CASCADE", "NO ACTION");
        
        $this->addForeignKey('FK_Value_entityId',
                '{{%eav_attribute_value}}', 'entityId', '{{%eav_entity}}', 'id', "CASCADE", "NO ACTION");
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
