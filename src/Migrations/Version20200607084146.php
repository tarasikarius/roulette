<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607084146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE bid_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bid (id INT NOT NULL, player_id INT NOT NULL, spin_id INT NOT NULL, cell INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4AF2B3F399E6F5DF ON bid (player_id)');
        $this->addSql('CREATE INDEX IDX_4AF2B3F39D159127 ON bid (spin_id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F399E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F39D159127 FOREIGN KEY (spin_id) REFERENCES spin (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE spin_user');
        $this->addSql('ALTER TABLE spin ADD winning_cell INT NOT NULL');
        $this->addSql('ALTER TABLE spin RENAME COLUMN cell TO initiator_id');
        $this->addSql('ALTER TABLE spin ADD CONSTRAINT FK_120A248F7DB3B714 FOREIGN KEY (initiator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_120A248F7DB3B714 ON spin (initiator_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE bid_id_seq CASCADE');
        $this->addSql('CREATE TABLE spin_user (spin_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(spin_id, user_id))');
        $this->addSql('CREATE INDEX idx_b9334c7d9d159127 ON spin_user (spin_id)');
        $this->addSql('CREATE INDEX idx_b9334c7da76ed395 ON spin_user (user_id)');
        $this->addSql('ALTER TABLE spin_user ADD CONSTRAINT fk_b9334c7d9d159127 FOREIGN KEY (spin_id) REFERENCES spin (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE spin_user ADD CONSTRAINT fk_b9334c7da76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE bid');
        $this->addSql('ALTER TABLE spin DROP CONSTRAINT FK_120A248F7DB3B714');
        $this->addSql('DROP INDEX IDX_120A248F7DB3B714');
        $this->addSql('ALTER TABLE spin ADD cell INT NOT NULL');
        $this->addSql('ALTER TABLE spin DROP initiator_id');
        $this->addSql('ALTER TABLE spin DROP winning_cell');
    }
}
