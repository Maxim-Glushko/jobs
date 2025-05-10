<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%persons}}`.
 */
class m250508_102807_create_persons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%persons}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->text()->notNull(),
            'position' => $this->text(),
            'contacts' => $this->json(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%persons}}');
    }
}
