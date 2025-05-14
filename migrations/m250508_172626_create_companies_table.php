<?php

use yii\db\Migration;

/**
 * Создание таблицы companies
 */
class m250508_172626_create_companies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%companies}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->text()->notNull(),
            'contacts' => $this->json(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Создаем индекс для поля name
        $this->createIndex(
            '{{%idx-companies-name}}',
            '{{%companies}}',
            'name(15)'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-companies-name}}', '{{%companies}}');
        $this->dropTable('{{%companies}}');
    }
}