<?php

use yii\db\Migration;

class m250522_135940_change_vacancies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('vacancies', 'interview_date', $this->date()->after('comment'));
        $this->createIndex('idx-vacancies-interview_date', 'vacancies', 'interview_date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-vacancies-interview_date', 'vacancies');
        $this->dropColumn('vacancies', 'idx-vacancies-interview_date');
    }
}
