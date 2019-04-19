<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190419092015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() 
        !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE content content VARCHAR(255) NOT NULL, 
        CHANGE comment comment TINYINT(1) NOT NULL, CHANGE date_article date_article DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 
        'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE content content VARCHAR(10000) NOT NULL COLLATE latin1_swedish_ci, 
        CHANGE comment comment TINYINT(1) DEFAULT NULL, CHANGE date_article date_article DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
