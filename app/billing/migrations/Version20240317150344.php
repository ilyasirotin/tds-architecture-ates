<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317150344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billing_cycle DROP CONSTRAINT fk_b5f9f4be6d28840d');
        $this->addSql('ALTER TABLE billing_cycle DROP CONSTRAINT fk_b5f9f4becf60e67c');
        $this->addSql('DROP INDEX uniq_b5f9f4be6d28840d');
        $this->addSql('DROP INDEX idx_b5f9f4becf60e67c');
        $this->addSql('ALTER TABLE billing_cycle DROP payment');
        $this->addSql('ALTER TABLE billing_cycle RENAME COLUMN owner TO account');
        $this->addSql('ALTER TABLE billing_cycle ADD CONSTRAINT FK_B5F9F4BE7D3656A4 FOREIGN KEY (account) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B5F9F4BE7D3656A4 ON billing_cycle (account)');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT fk_723705d1527edb25');
        $this->addSql('DROP INDEX idx_723705d1527edb25');
        $this->addSql('ALTER TABLE transaction ADD description TEXT NOT NULL');
        $this->addSql('ALTER TABLE transaction DROP task');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE billing_cycle DROP CONSTRAINT FK_B5F9F4BE7D3656A4');
        $this->addSql('DROP INDEX IDX_B5F9F4BE7D3656A4');
        $this->addSql('ALTER TABLE billing_cycle ADD payment INT DEFAULT NULL');
        $this->addSql('ALTER TABLE billing_cycle RENAME COLUMN account TO owner');
        $this->addSql('ALTER TABLE billing_cycle ADD CONSTRAINT fk_b5f9f4be6d28840d FOREIGN KEY (payment) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE billing_cycle ADD CONSTRAINT fk_b5f9f4becf60e67c FOREIGN KEY (owner) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_b5f9f4be6d28840d ON billing_cycle (payment)');
        $this->addSql('CREATE INDEX idx_b5f9f4becf60e67c ON billing_cycle (owner)');
        $this->addSql('ALTER TABLE transaction ADD task INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction DROP description');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT fk_723705d1527edb25 FOREIGN KEY (task) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_723705d1527edb25 ON transaction (task)');
    }
}
