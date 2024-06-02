<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240212102724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert default data';
    }

    public function up(Schema $schema): void
    {
        // Users
        $this->addSql('INSERT INTO user (id, lastname, firstname, apikey, password, userName) VALUES
            (1, "Andy", "Warhol", "2b096db96bd3647961e19342f37a95ca4356f4cdc2c7ab010c80b5c2d6b9dbb3", null, "andyW"),
            (2, "Stanley", "Kubrick", "9269e615a5fd2b5224d24b22ad28919e593d7bd76c17b5b9716a29b57d897da5", null, "kubS"),
            (3, "Steven", "Spielberg", "7db059e4b5e1d47849a2e613763783a8d955fe357376a2364f15eb5de3f0156f", null, "steve")
        ');

        // Articles
        $this->addSql('INSERT INTO article (title, content, userId, releaseDate, status) VALUES
            ("Le retour du roi", "Quel film de folie, le suspens est à son comble: Frodon va-t\'il réussir sa quête?", 1, null, "draft"),
            ("Un nouvel espoir", "Ce film a permis de faire connaître au monde R2D2.", 1, "'.(new \DateTime())->format(DATE_ATOM).'", "published"),
            ("Retour vers le futur", "Film de zinzin mais le début d\'une trilogie d\'anthologie!", 2, "'.(new \DateTime())->format(DATE_ATOM).'", "published"),
            ("Le retour du jedi", "Conclusion d\'une trilogie phare de la SF. R2D2 héros de l\'histoire?", 2, "'.(new \DateTime())->sub(new \DateInterval('P2W'))->format(DATE_ATOM).'", "published"),
            ("2001 L\'Odyssée de l\'espace", "Un peu perché ce film? J\'ai bien aimé la musique.", 3, "'.(new \DateTime())->add(new \DateInterval('P1W'))->format(DATE_ATOM).'", "draft"),
            ("2001 L\'Odyssée de l\'espace", "Un peu perché ce film? J\'ai bien aimé le.", 3, "'.(new \DateTime())->add(new \DateInterval('P1W'))->format(DATE_ATOM).'", "deleted")
        ');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE user');
        $this->addSql('TRUNCATE TABLE article');
    }
}
