<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240812045151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marks ADD subject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marks ADD CONSTRAINT FK_3C6AFA5323EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('CREATE INDEX IDX_3C6AFA5323EDC87 ON marks (subject_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marks DROP FOREIGN KEY FK_3C6AFA5323EDC87');
        $this->addSql('DROP INDEX IDX_3C6AFA5323EDC87 ON marks');
        $this->addSql('ALTER TABLE marks DROP subject_id');
    }
}
