<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220702185028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change movie rating relation to OneToOne';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_214ebb578f93b6fc');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_214EBB578F93B6FC ON movie_rating (movie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_214EBB578F93B6FC');
        $this->addSql('CREATE INDEX idx_214ebb578f93b6fc ON movie_rating (movie_id)');
    }
}
