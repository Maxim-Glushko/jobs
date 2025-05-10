<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%persons_companies}}`.
 */
class m250509_174124_create_persons_companies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%persons_companies}}', [
            'person_id' => $this->integer()->unsigned()->notNull(),
            'company_id' => $this->integer()->unsigned()->notNull(),
            'PRIMARY KEY(person_id, company_id)'
        ]);

        $this->addForeignKey(
            'fk-persons_companies-person_id',
            '{{%persons_companies}}',
            'person_id',
            '{{%persons}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-persons_companies-company_id',
            '{{%persons_companies}}',
            'company_id',
            '{{%companies}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-persons_companies-company_id', '{{%persons_companies}}');
        $this->dropForeignKey('fk-persons_companies-person_id', '{{%persons_companies}}');
        $this->dropTable('{{%persons_companies}}');
    }
}
