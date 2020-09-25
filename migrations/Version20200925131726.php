<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925131726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE humidity_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE location_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE temperature_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql(
            'CREATE TABLE humidity (id INT NOT NULL, location_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value INT NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_69FC77C264D218E ON humidity (location_id)');
        $this->addSql(
            'CREATE TABLE location (id INT NOT NULL, city VARCHAR(255) NOT NULL, lon DOUBLE PRECISION NOT NULL, lat DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql(
            'CREATE TABLE temperature (id INT NOT NULL, location_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_BE4E2A6C64D218E ON temperature (location_id)');
        $this->addSql(
            'ALTER TABLE humidity ADD CONSTRAINT FK_69FC77C264D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE temperature ADD CONSTRAINT FK_BE4E2A6C64D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE humidity DROP CONSTRAINT FK_69FC77C264D218E');
        $this->addSql('ALTER TABLE temperature DROP CONSTRAINT FK_BE4E2A6C64D218E');
        $this->addSql('DROP SEQUENCE humidity_seq CASCADE');
        $this->addSql('DROP SEQUENCE location_seq CASCADE');
        $this->addSql('DROP SEQUENCE temperature_seq CASCADE');
        $this->addSql('DROP TABLE humidity');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE temperature');
    }
}
