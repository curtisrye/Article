<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531141640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Trace log table';
    }

    public function up(Schema $schema): void
    {
        // Prevent an accidental automatic deployment.
        if ($this->sm->tableExists('trace_log')){
            return;
        }

        $sql = <<<'SQL'
        SET FOREIGN_KEY_CHECKS=0;
            CREATE TABLE IF NOT EXISTS `trace_log` (
                `id` int NOT NULL AUTO_INCREMENT,
                `modelName` varchar(100) NOT NULL,
                `oldFields` JSON,
                `newFields` JSON,
                `updatedAt` datetime NOT NULL,
                `updatedBy` int,
                PRIMARY KEY (`id`)
            );
        SET FOREIGN_KEY_CHECKS=1;
        SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE trace_log');
    }
}
