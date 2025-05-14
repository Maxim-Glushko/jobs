<?php
use yii\db\Migration;

/**
 * Class m250514_055256_convert_tables_to_utf8mb4
 * Перевод всех таблиц и текстовых полей на utf8mb4.
 */
class m250514_055256_convert_tables_to_utf8mb4 extends Migration
{
    public function safeUp()
    {
        $this->execute("SET NAMES utf8mb4;");
        $this->execute("SET CHARACTER SET utf8mb4;");
        $this->execute("SET collation_connection = 'utf8mb4_unicode_ci';");

        $db = $this->getDb();
        $tables = $db->createCommand('SHOW TABLES')->queryColumn();

        foreach ($tables as $table) {
            $this->execute("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        }
    }

    public function safeDown()
    {
        $db = $this->getDb();
        $tables = $db->createCommand('SHOW TABLES')->queryColumn();
        foreach ($tables as $table) {
            $this->execute("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        }
    }
}