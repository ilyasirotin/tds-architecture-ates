<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315193213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX uniq_527edb256ac664ef RENAME TO UNIQ_527EDB25182694FC');
        $this->addSql('ALTER TABLE "user" ADD account INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6497D3656A4 FOREIGN KEY (account) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497D3656A4 ON "user" (account)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER INDEX uniq_527edb25182694fc RENAME TO uniq_527edb256ac664ef');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6497D3656A4');
        $this->addSql('DROP INDEX UNIQ_8D93D6497D3656A4');
        $this->addSql('ALTER TABLE "user" DROP account');
    }
}
