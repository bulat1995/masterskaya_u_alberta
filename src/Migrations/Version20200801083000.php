<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200801083000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE acts (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, INDEX IDX_6A10A67719EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE works (id INT AUTO_INCREMENT NOT NULL, catalog_id INT DEFAULT NULL, document_id INT DEFAULT NULL, act_id INT DEFAULT NULL, client_id INT DEFAULT NULL, count INT NOT NULL, price DOUBLE PRECISION NOT NULL, date_create DATETIME NOT NULL, date_payed DATETIME DEFAULT NULL, INDEX IDX_F6E50243CC3C66FC (catalog_id), INDEX IDX_F6E50243C33F7837 (document_id), INDEX IDX_F6E50243D1A55B28 (act_id), INDEX IDX_F6E5024319EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devices (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(400) NOT NULL, logo VARCHAR(200) DEFAULT NULL, lft INT NOT NULL, rgt INT NOT NULL, lvl INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(400) NOT NULL, position_name VARCHAR(400) NOT NULL, position_name_short VARCHAR(400) NOT NULL, boss_name VARCHAR(400) NOT NULL, in_face VARCHAR(400) NOT NULL, doc_name VARCHAR(400) NOT NULL, inn VARCHAR(400) NOT NULL, kpp VARCHAR(400) NOT NULL, qr_code VARCHAR(400) NOT NULL, qr_code_width VARCHAR(400) NOT NULL, rss VARCHAR(400) NOT NULL, address VARCHAR(400) NOT NULL, bank_info VARCHAR(800) NOT NULL, bik VARCHAR(400) NOT NULL, ks VARCHAR(400) NOT NULL, contacts VARCHAR(800) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogs (id INT AUTO_INCREMENT NOT NULL, device_id INT DEFAULT NULL, name VARCHAR(400) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, count_name VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, INDEX IDX_F3AD370A94A4C7D4 (device_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_doc_template (catalog_id INT NOT NULL, doc_template_id INT NOT NULL, INDEX IDX_C7AB3816CC3C66FC (catalog_id), INDEX IDX_C7AB38165653D501 (doc_template_id), PRIMARY KEY(catalog_id, doc_template_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DocTemplate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, document VARCHAR(200) NOT NULL, sub_doc VARCHAR(200) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, doc_template_id INT DEFAULT NULL, client_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, date_open DATE DEFAULT NULL, date_closed DATE DEFAULT NULL, date_payed DATETIME DEFAULT NULL, INDEX IDX_A2B072885653D501 (doc_template_id), INDEX IDX_A2B0728819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_1483A5E992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_1483A5E9A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_1483A5E9C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(400) NOT NULL, address VARCHAR(400) NOT NULL, boss_name VARCHAR(200) NOT NULL, position_name VARCHAR(200) NOT NULL, bank_info VARCHAR(800) NOT NULL, in_face VARCHAR(400) NOT NULL, doc_name VARCHAR(400) NOT NULL, inn VARCHAR(400) NOT NULL, kpp VARCHAR(400) NOT NULL, rss VARCHAR(400) NOT NULL, bik VARCHAR(400) NOT NULL, ks VARCHAR(400) NOT NULL, contacts VARCHAR(800) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acts ADD CONSTRAINT FK_6A10A67719EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E50243CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalogs (id)');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E50243C33F7837 FOREIGN KEY (document_id) REFERENCES documents (id)');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E50243D1A55B28 FOREIGN KEY (act_id) REFERENCES acts (id)');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E5024319EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE catalogs ADD CONSTRAINT FK_F3AD370A94A4C7D4 FOREIGN KEY (device_id) REFERENCES devices (id)');
        $this->addSql('ALTER TABLE catalog_doc_template ADD CONSTRAINT FK_C7AB3816CC3C66FC FOREIGN KEY (catalog_id) REFERENCES catalogs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE catalog_doc_template ADD CONSTRAINT FK_C7AB38165653D501 FOREIGN KEY (doc_template_id) REFERENCES DocTemplate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072885653D501 FOREIGN KEY (doc_template_id) REFERENCES DocTemplate (id)');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B0728819EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE works DROP FOREIGN KEY FK_F6E50243D1A55B28');
        $this->addSql('ALTER TABLE catalogs DROP FOREIGN KEY FK_F3AD370A94A4C7D4');
        $this->addSql('ALTER TABLE works DROP FOREIGN KEY FK_F6E50243CC3C66FC');
        $this->addSql('ALTER TABLE catalog_doc_template DROP FOREIGN KEY FK_C7AB3816CC3C66FC');
        $this->addSql('ALTER TABLE catalog_doc_template DROP FOREIGN KEY FK_C7AB38165653D501');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072885653D501');
        $this->addSql('ALTER TABLE works DROP FOREIGN KEY FK_F6E50243C33F7837');
        $this->addSql('ALTER TABLE acts DROP FOREIGN KEY FK_6A10A67719EB6921');
        $this->addSql('ALTER TABLE works DROP FOREIGN KEY FK_F6E5024319EB6921');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B0728819EB6921');
        $this->addSql('DROP TABLE acts');
        $this->addSql('DROP TABLE works');
        $this->addSql('DROP TABLE devices');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE catalogs');
        $this->addSql('DROP TABLE catalog_doc_template');
        $this->addSql('DROP TABLE DocTemplate');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE clients');
    }
}
