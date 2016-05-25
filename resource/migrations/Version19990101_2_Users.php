<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version19990101_2_Users extends AbstractMigration
{
    /**
     *  Run the migrations
     */
    public function up(Schema $schema)
    {
        // users 的資料比較特殊, 通常一開始會在 database 直接建立
        // 所以這裡面就直接略過
        $this->createTable();
    }

    /**
     *  Reverse the migrations.
     */
    public function down(Schema $schema)
    {
        // 為了資料安全, 這裡不做任何處理
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------
    private function createTable()
    {
/*
        $sql = <<<EOD

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `account` varchar(32) NOT NULL,
  `password` char(77) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Pbkdf2',
  `role_names` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `properties` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account` (`account`),
  ADD KEY `account_password_index` (`account`,`password`),
  ADD KEY `status` (`status`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

EOD;
        $this->addSql($sql);
*/
    }

}
