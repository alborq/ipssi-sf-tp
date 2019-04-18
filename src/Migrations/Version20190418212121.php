<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190418212121 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE article (
                          id INT AUTO_INCREMENT NOT NULL,
                          title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
                          content LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci,
                          creation_date DATETIME NOT NULL,
                          PRIMARY KEY(id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\'
                        ');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE comment (
                          id INT AUTO_INCREMENT NOT NULL,
                          article_id INT DEFAULT NULL,
                          content VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
                          INDEX IDX_9474526C7294869C (article_id),
                          PRIMARY KEY(id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\'
                        ');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE game (
                          id INT AUTO_INCREMENT NOT NULL,
                          name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
                          start_time DATETIME NOT NULL,
                          amount INT NOT NULL,
                          PRIMARY KEY(id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\'
                        ');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE game_user (
                          game_id INT NOT NULL,
                          user_id INT NOT NULL,
                          INDEX IDX_6686BA65E48FD905 (game_id),
                          INDEX IDX_6686BA65A76ED395 (user_id),
                          PRIMARY KEY(game_id, user_id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\'
                        ');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE party (
                          id INT AUTO_INCREMENT NOT NULL,
                          creation_date DATETIME NOT NULL,
                          game_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
                          PRIMARY KEY(id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\'
                        ');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE user (
                          id INT AUTO_INCREMENT NOT NULL,
                          email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci,
                          roles LONGTEXT NOT NULL COLLATE utf8mb4_bin,
                          password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
                          amount INT NOT NULL,
                          UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
                          PRIMARY KEY(id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\'
');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE user_game (
                          user_id INT NOT NULL,
                          game_id INT NOT NULL,
                          INDEX IDX_59AA7D45A76ED395 (user_id),
                          INDEX IDX_59AA7D45E48FD905 (game_id),
                          PRIMARY KEY(user_id, game_id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\'
                        ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE article');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE comment');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE game');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE game_user');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE party');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE user');
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE user_game');
    }
}
