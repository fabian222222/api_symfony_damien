<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221203192502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F8119EB6921 ON address (client_id)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404555713BC80');
        $this->addSql('DROP INDEX IDX_C74404555713BC80 ON client');
        $this->addSql('ALTER TABLE client DROP addresses_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8119EB6921');
        $this->addSql('DROP INDEX IDX_D4E6F8119EB6921 ON address');
        $this->addSql('ALTER TABLE address DROP client_id');
        $this->addSql('ALTER TABLE client ADD addresses_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404555713BC80 FOREIGN KEY (addresses_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_C74404555713BC80 ON client (addresses_id)');
    }
}
