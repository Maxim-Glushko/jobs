<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%persons_interactions}}`.
 */
class m250509_174151_create_persons_interactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%persons_interactions}}', [
            'person_id' => $this->integer()->unsigned()->notNull(),
            'interaction_id' => $this->integer()->unsigned()->notNull(),
            'PRIMARY KEY(person_id, interaction_id)'
        ]);

        $this->addForeignKey(
            'fk-persons_interactions-person_id',
            '{{%persons_interactions}}',
            'person_id',
            '{{%persons}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-persons_interactions-interaction_id',
            '{{%persons_interactions}}',
            'interaction_id',
            '{{%interactions}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-persons_interactions-interaction_id', '{{%persons_interactions}}');
        $this->dropForeignKey('fk-persons_interactions-person_id', '{{%persons_interactions}}');
        $this->dropTable('{{%persons_interactions}}');
    }
}