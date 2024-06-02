<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240210110712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add userName column';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD COLUMN userName varchar(50)');
        $this->addSql('CREATE UNIQUE INDEX username_idx ON user (userName)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP COLUMN user');
    }
}
