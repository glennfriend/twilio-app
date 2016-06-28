<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160517_User_Logs extends AbstractMigration
{
    /**
     *  Run the migrations
     */
    public function up(Schema $schema)
    {
        $this->createTable();
    }

    /**
     *  Reverse the migrations.
     */
    public function down(Schema $schema)
    {
        // 不予許 drop table
        // $schema->dropTable('user_logs');
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------
    private function createTable()
    {
        $sql = <<<EOD

CREATE TABLE IF NOT EXISTS `user_logs` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `actions` varchar(80) NOT NULL COMMENT '[login-xx],[logout-xx],[xx-add],[xx-update],[xx-delete],[xx--enable],[xx-disable]',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL,
  `ipn` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_logs`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

EOD;
        $this->addSql($sql);
    }

}
