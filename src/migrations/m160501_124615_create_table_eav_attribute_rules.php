<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_eav_attribute_rules`.
 */
class m160501_124615_create_table_eav_attribute_rules extends Migration
{
    const TABLE_NAME = '{{%eav_attribute_rules}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = $this->db->driverName == 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
            : null;

        $this->createTable(
            self::TABLE_NAME,
            [
                'id' => $this->primaryKey(),
                'attributeId' => $this->integer(11)->defaultValue(0),
                'rules' => $this->text(),
            ],
            $options
        );

        $this->addForeignKey(
            'FK_Rules_attributeId',
            self::TABLE_NAME,
            'attributeId',
            '{{%eav_attribute}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_Rules_attributeId', self::TABLE_NAME);
        $this->dropTable('eav_attribute_rules');
    }
}
