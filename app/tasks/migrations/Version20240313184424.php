<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313184424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk_527edb25f675f31b');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk_527edb2559ec7d60');
        $this->addSql('DROP INDEX idx_527edb25f675f31b');
        $this->addSql('DROP INDEX idx_527edb2559ec7d60');
        $this->addSql('ALTER TABLE task ADD author INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD assignee INT NOT NULL');
        $this->addSql('ALTER TABLE task DROP author_id');
        $this->addSql('ALTER TABLE task DROP assignee_id');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25BDAFD8C8 FOREIGN KEY (author) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257C9DFC0C FOREIGN KEY (assignee) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_527EDB25BDAFD8C8 ON task (author)');
        $this->addSql('CREATE INDEX IDX_527EDB257C9DFC0C ON task (assignee)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25BDAFD8C8');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB257C9DFC0C');
        $this->addSql('DROP INDEX IDX_527EDB25BDAFD8C8');
        $this->addSql('DROP INDEX IDX_527EDB257C9DFC0C');
        $this->addSql('ALTER TABLE task ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD assignee_id INT NOT NULL');
        $this->addSql('ALTER TABLE task DROP author');
        $this->addSql('ALTER TABLE task DROP assignee');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT fk_527edb25f675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT fk_527edb2559ec7d60 FOREIGN KEY (assignee_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_527edb25f675f31b ON task (author_id)');
        $this->addSql('CREATE INDEX idx_527edb2559ec7d60 ON task (assignee_id)');
    }
}
