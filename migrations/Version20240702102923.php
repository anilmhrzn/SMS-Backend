<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702102923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendance (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_6DE30D91CB944F1A (student_id), INDEX IDX_6DE30D91A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, date DATE NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_38BBA6C623EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marks (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, exam_id INT DEFAULT NULL, mark_obtained VARCHAR(255) NOT NULL, INDEX IDX_3C6AFA53CB944F1A (student_id), INDEX IDX_3C6AFA53578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, number JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_subject (student_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_16F88B82CB944F1A (student_id), INDEX IDX_16F88B8223EDC87 (subject_id), PRIMARY KEY(student_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D64923EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_student (user_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_EF2EB139A76ED395 (user_id), INDEX IDX_EF2EB139CB944F1A (student_id), PRIMARY KEY(user_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D91CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D91A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C623EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE marks ADD CONSTRAINT FK_3C6AFA53CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE marks ADD CONSTRAINT FK_3C6AFA53578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE student_subject ADD CONSTRAINT FK_16F88B82CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_subject ADD CONSTRAINT FK_16F88B8223EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64923EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE user_student ADD CONSTRAINT FK_EF2EB139A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_student ADD CONSTRAINT FK_EF2EB139CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D91CB944F1A');
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D91A76ED395');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C623EDC87');
        $this->addSql('ALTER TABLE marks DROP FOREIGN KEY FK_3C6AFA53CB944F1A');
        $this->addSql('ALTER TABLE marks DROP FOREIGN KEY FK_3C6AFA53578D5E91');
        $this->addSql('ALTER TABLE student_subject DROP FOREIGN KEY FK_16F88B82CB944F1A');
        $this->addSql('ALTER TABLE student_subject DROP FOREIGN KEY FK_16F88B8223EDC87');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64923EDC87');
        $this->addSql('ALTER TABLE user_student DROP FOREIGN KEY FK_EF2EB139A76ED395');
        $this->addSql('ALTER TABLE user_student DROP FOREIGN KEY FK_EF2EB139CB944F1A');
        $this->addSql('DROP TABLE attendance');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE marks');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_subject');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_student');
    }
}
