<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202134934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Implement technology and source for activity WITCHER-350';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD technology INT NOT NULL, ADD source VARCHAR(400) DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AF463524D FOREIGN KEY (technology) REFERENCES technology (id)');
        $this->addSql('CREATE INDEX IDX_activity_technology ON activity (technology)');

        $this->addSql('DROP INDEX IDX_technology_displayOrder ON technology');
        $this->addSql('DROP INDEX UK_technology_handle ON technology');
        $this->addSql('ALTER TABLE technology DROP friendly_name, DROP display_order, CHANGE handle name VARCHAR(30) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UK_technology_name ON technology (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AF463524D');
        $this->addSql('DROP INDEX IDX_activity_technology ON activity');
        $this->addSql('ALTER TABLE activity DROP technology, DROP source');

        $this->addSql('DROP INDEX UK_technology_name ON technology');
        $this->addSql('ALTER TABLE technology ADD friendly_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD display_order INT DEFAULT NULL, CHANGE name handle VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE INDEX IDX_technology_displayOrder ON technology (display_order)');
        $this->addSql('CREATE UNIQUE INDEX UK_technology_handle ON technology (handle)');
    }
}
