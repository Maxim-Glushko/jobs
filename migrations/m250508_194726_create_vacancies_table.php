<?php

use yii\db\Migration;

/**
 * Создание таблицы vacancies
 */
class m250508_194726_create_vacancies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vacancies}}', [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->text()->notNull(),
            'text' => $this->text(),
            'company_id' => $this->integer()->unsigned(),
            'contacts' => $this->json(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Создаем внешний ключ для company_id
        $this->addForeignKey(
            '{{%fk-vacancies-company_id}}',
            '{{%vacancies}}',
            'company_id',
            '{{%companies}}',
            'id',
            'CASCADE'
        );

        // Создаем индекс для поля company_id
        $this->createIndex(
            '{{%idx-vacancies-company_id}}',
            '{{%vacancies}}',
            'company_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-vacancies-company_id}}', '{{%vacancies}}');
        $this->dropIndex('{{%idx-vacancies-company_id}}', '{{%vacancies}}');
        $this->dropTable('{{%vacancies}}');
    }
}