<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220701205618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add movie rating table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE movie_rating_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE movie_rating (id INT NOT NULL, movie_id INT NOT NULL, imdb DOUBLE PRECISION DEFAULT NULL, rotten_tomatto DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_214EBB578F93B6FC ON movie_rating (movie_id)');
        $this->addSql('ALTER TABLE movie_rating ADD CONSTRAINT FK_214EBB578F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE movie_rating_id_seq CASCADE');
        $this->addSql('DROP TABLE movie_rating');
    }
}
