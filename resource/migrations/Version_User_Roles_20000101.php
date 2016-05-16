<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version_User_Roles_20000101 extends AbstractMigration
{
    /**
     *  Run the migrations
     */
    public function up(Schema $schema)
    {
        // user_roles 的資料比較特殊, 通常一開始會在 database 直接建立
        // 所以這裡面就直接略過
        $this->createTable();
        $this->createData();
    }

    /**
     *  Reverse the migrations.
     */
    public function down(Schema $schema)
    {
        // $schema->dropTable('user_roles');
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------
    private function createTable()
    {
/*
        $sql = <<<EOD

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`name`);

ALTER TABLE `user_roles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

EOD;
        $this->addSql($sql);
*/
    }

    private function createData()
    {
/*
        $sql = <<<EOD

INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES
    (1, 'login', 'Login Authority'),
    (2, 'type-2', '保留'),
    (3, 'type-3', '保留'),
    (4, 'type-4', '保留'),
    (5, 'type-5', '保留'),
    (6, 'type-6', '保留'),
    (7, 'type-7', '保留'),
    (8, 'type-8', '保留'),
    (9, 'type-9', '保留'),
    (10, 'type-10', '保留'),
    (11, 'type-11', '保留'),
    (12, 'type-12', '保留'),
    (13, 'type-13', '保留'),
    (14, 'type-14', '保留'),
    (15, 'type-15', '保留'),
    (16, 'type-16', '保留'),
    (17, 'type-17', '保留'),
    (18, 'type-18', '保留'),
    (19, 'type-19', '保留'),
    (20, 'type-20', '保留'),
    (21, 'customer-service', 'Customer Service Authority');

EOD;
        $this->addSql($sql);
*/
    }

}
