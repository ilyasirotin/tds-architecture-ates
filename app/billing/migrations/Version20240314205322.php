<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314205322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE billing_cycle_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cost_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account (id INT NOT NULL, owner INT NOT NULL, public_id UUID NOT NULL, balance NUMERIC(10, 0) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4CF60E67C ON account (owner)');
        $this->addSql('COMMENT ON COLUMN account.public_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE billing_cycle (id INT NOT NULL, payment INT DEFAULT NULL, owner INT NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B5F9F4BE6D28840D ON billing_cycle (payment)');
        $this->addSql('CREATE INDEX IDX_B5F9F4BECF60E67C ON billing_cycle (owner)');
        $this->addSql('COMMENT ON COLUMN billing_cycle.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE cost (id INT NOT NULL, credit NUMERIC(10, 0) NOT NULL, debit NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, public_id UUID NOT NULL, amount NUMERIC(10, 0) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT NOT NULL, status VARCHAR(256) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payment.public_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE task (id INT NOT NULL, author INT NOT NULL, assignee INT NOT NULL, cost INT NOT NULL, public_id UUID NOT NULL, title VARCHAR(256) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25BDAFD8C8 ON task (author)');
        $this->addSql('CREATE INDEX IDX_527EDB257C9DFC0C ON task (assignee)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB256AC664EF ON task (cost)');
        $this->addSql('COMMENT ON COLUMN task.public_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, account INT NOT NULL, task INT NOT NULL, billing_cycle INT NOT NULL, payment INT DEFAULT NULL, public_id UUID NOT NULL, credit NUMERIC(10, 0) NOT NULL, debit NUMERIC(10, 0) NOT NULL, type VARCHAR(256) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D17D3656A4 ON transaction (account)');
        $this->addSql('CREATE INDEX IDX_723705D1527EDB25 ON transaction (task)');
        $this->addSql('CREATE INDEX IDX_723705D1B5F9F4BE ON transaction (billing_cycle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D16D28840D ON transaction (payment)');
        $this->addSql('COMMENT ON COLUMN transaction.public_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4CF60E67C FOREIGN KEY (owner) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE billing_cycle ADD CONSTRAINT FK_B5F9F4BE6D28840D FOREIGN KEY (payment) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE billing_cycle ADD CONSTRAINT FK_B5F9F4BECF60E67C FOREIGN KEY (owner) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25BDAFD8C8 FOREIGN KEY (author) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257C9DFC0C FOREIGN KEY (assignee) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256AC664EF FOREIGN KEY (cost) REFERENCES cost (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D17D3656A4 FOREIGN KEY (account) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1527EDB25 FOREIGN KEY (task) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B5F9F4BE FOREIGN KEY (billing_cycle) REFERENCES billing_cycle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D16D28840D FOREIGN KEY (payment) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE account_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE billing_cycle_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cost_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE task_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A4CF60E67C');
        $this->addSql('ALTER TABLE billing_cycle DROP CONSTRAINT FK_B5F9F4BE6D28840D');
        $this->addSql('ALTER TABLE billing_cycle DROP CONSTRAINT FK_B5F9F4BECF60E67C');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25BDAFD8C8');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB257C9DFC0C');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB256AC664EF');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D17D3656A4');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1527EDB25');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1B5F9F4BE');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D16D28840D');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE billing_cycle');
        $this->addSql('DROP TABLE cost');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE transaction');
    }
}
