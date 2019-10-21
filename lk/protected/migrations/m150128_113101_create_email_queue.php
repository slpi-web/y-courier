<?php

class m150128_113101_create_email_queue extends CDbMigration
{
    public function up()
    {
        $this->execute("
			CREATE TABLE IF NOT EXISTS `email_queue` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `data` longtext NOT NULL,
              `status` int(1) unsigned NOT NULL,
              PRIMARY KEY (`id`),
              KEY `timestamp` (`timestamp`,`status`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		");
    }

    public function down()
    {
        echo "m150128_113101_create_email_queue does not support migration down.\n";
        return false;
    }

    /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}