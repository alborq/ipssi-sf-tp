<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417092229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bet CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_game id_game INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_article id_article INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jouer RENAME INDEX jouer_user0_fk TO IDX_825E5AED6B3CA4B');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, 
        ADD password VARCHAR(255) NOT NULL, DROP email, DROP pwd, DROP level, DROP amount');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 
        'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE bet CHANGE id_game id_game INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE id_article id_article INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE jouer RENAME INDEX idx_825e5aed6b3ca4b TO jouer_user0_FK');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user ADD pwd VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, ADD level INT NOT NULL, ADD amount INT NOT NULL, 
        DROP username, DROP roles, CHANGE password email VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci');
    }
}
