<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%interactions}}`.
 */
class m250509_172327_create_interactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%interactions}}', [
            'id' => $this->primaryKey()->unsigned(),
            'text' => $this->text(),
            'result' => $this->text(),
            'vacancy_id' => $this->integer()->unsigned(),
            'date' => $this->date(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Создаем внешний ключ для vacancy_id
        $this->addForeignKey(
            '{{%fk-interactions-vacancy_id}}',
            '{{%interactions}}',
            'vacancy_id',
            '{{%vacancies}}',
            'id',
            'CASCADE'
        );

        // Создаем индекс для поля vacancy_id
        $this->createIndex(
            '{{%idx-interactions-vacancy_id}}',
            '{{%interactions}}',
            'vacancy_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-interactions-vacancy_id}}', '{{%interactions}}');
        $this->dropIndex('{{%idx-interactions-vacancy_id}}', '{{%interactions}}');
        $this->dropTable('{{%interactions}}');
    }

}
