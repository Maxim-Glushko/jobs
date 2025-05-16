<?php

use yii\db\Migration;

class m250515_101555_change_companies_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('companies', 'status', $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->after('comment'));
        $this->createIndex('idx-companies-status', 'companies', 'status');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-companies-status', 'companies');
        $this->dropColumn('companies', 'status');
    }
}
