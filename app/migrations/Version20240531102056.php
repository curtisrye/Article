<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240531102056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Tag table';
    }

    public function up(Schema $schema): void
    {
        // Prevent an accidental automatic deployment.
        if ($this->sm->tableExists('tag')){
            return;
        }

        $sql = <<<'SQL'
        SET FOREIGN_KEY_CHECKS=0;
            CREATE TABLE IF NOT EXISTS `tag` (
                `id` int NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `counter` int NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `UC_TAG_NAME` (`name`)
            );
        SET FOREIGN_KEY_CHECKS=1;
        SQL;

        $this->addSql($sql);

    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE tag');
    }
}
