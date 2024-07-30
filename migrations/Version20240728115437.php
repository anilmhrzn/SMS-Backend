<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240728115437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE semester (id INT AUTO_INCREMENT NOT NULL, semester INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exam ADD semester_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C64A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C64A798B6F ON exam (semester_id)');
        $this->addSql('ALTER TABLE student ADD semester_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF334A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('CREATE INDEX IDX_B723AF334A798B6F ON student (semester_id)');
        $this->addSql('ALTER TABLE subject ADD semester_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A4A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('CREATE INDEX IDX_FBCE3E7A4A798B6F ON subject (semester_id)');
        $this->addSql('ALTER TABLE user ADD semester_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494A798B6F ON user (semester_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C64A798B6F');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF334A798B6F');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A4A798B6F');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494A798B6F');
        $this->addSql('DROP TABLE semester');
        $this->addSql('DROP INDEX IDX_8D93D6494A798B6F ON user');
        $this->addSql('ALTER TABLE user DROP semester_id');
        $this->addSql('DROP INDEX IDX_FBCE3E7A4A798B6F ON subject');
        $this->addSql('ALTER TABLE subject DROP semester_id');
        $this->addSql('DROP INDEX IDX_38BBA6C64A798B6F ON exam');
        $this->addSql('ALTER TABLE exam DROP semester_id');
        $this->addSql('DROP INDEX IDX_B723AF334A798B6F ON student');
        $this->addSql('ALTER TABLE student DROP semester_id');
    }
}
