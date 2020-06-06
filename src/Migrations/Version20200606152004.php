<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606152004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE round_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE spin_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE round (id INT NOT NULL, closed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE spin (id INT NOT NULL, round_id INT NOT NULL, cell INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_120A248FA6005CA0 ON spin (round_id)');
        $this->addSql('CREATE TABLE spin_user (spin_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(spin_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B9334C7D9D159127 ON spin_user (spin_id)');
        $this->addSql('CREATE INDEX IDX_B9334C7DA76ED395 ON spin_user (user_id)');
        $this->addSql('ALTER TABLE spin ADD CONSTRAINT FK_120A248FA6005CA0 FOREIGN KEY (round_id) REFERENCES round (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE spin_user ADD CONSTRAINT FK_B9334C7D9D159127 FOREIGN KEY (spin_id) REFERENCES spin (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE spin_user ADD CONSTRAINT FK_B9334C7DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE spin DROP CONSTRAINT FK_120A248FA6005CA0');
        $this->addSql('ALTER TABLE spin_user DROP CONSTRAINT FK_B9334C7D9D159127');
        $this->addSql('DROP SEQUENCE round_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE spin_id_seq CASCADE');
        $this->addSql('DROP TABLE round');
        $this->addSql('DROP TABLE spin');
        $this->addSql('DROP TABLE spin_user');
    }
}
