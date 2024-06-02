<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240209132943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create all tables to use application';
    }

    public function up(Schema $schema): void
    {
        // Prevent an accidental automatic deployment.
        if ($this->sm->tablesExist(['user', 'article'])) {

            return;
        }

        $sql = <<<'SQL'
        SET FOREIGN_KEY_CHECKS=0;
            CREATE TABLE IF NOT EXISTS `user` (
                `id` int NOT NULL AUTO_INCREMENT,
                `lastname` varchar(100) NOT NULL,
                `firstname` varchar(100) NOT NULL,
                `apiKey` varchar(255),
                PRIMARY KEY (`id`),
                UNIQUE KEY `UC_API_KEY` (`apiKey`)
            );

            CREATE TABLE IF NOT EXISTS `article` (
                `id` int NOT NULL AUTO_INCREMENT,
                `title` varchar(128) NOT NULL,
                `content` varchar(2000) NOT NULL,
                `userId` int NOT NULL,
                `releaseDate` datetime DEFAULT NULL,
                `status` varchar(25) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `IDX_ARTICLE_USER` (`userId`),
                CONSTRAINT `FK_ARTICLE_USER` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
            );
        SET FOREIGN_KEY_CHECKS=1;
        SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        throw new \Exception('Please execute the doctrine migration command with the --drop-database option, instead of execute this down step.');
    }
}
