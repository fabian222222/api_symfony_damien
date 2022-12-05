<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221204203341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939889F92C4E');
        $this->addSql('DROP INDEX IDX_F529939889F92C4E ON `order`');
        $this->addSql('ALTER TABLE `order` ADD address_used VARCHAR(255) NOT NULL, DROP address_used_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD address_used_id INT DEFAULT NULL, DROP address_used');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939889F92C4E FOREIGN KEY (address_used_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_F529939889F92C4E ON `order` (address_used_id)');
    }
}
