<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214082657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Increase friendly_name column length';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_type CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE business_branch CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE country CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE currency CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');

        $this->addSql('ALTER TABLE industry CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE priority CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project_sale_type CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE status CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');

        $this->addSql('ALTER TABLE task CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE technology CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tracker_task_type CHANGE friendly_name friendly_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_type CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE business_branch CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE country CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE currency CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('ALTER TABLE industry CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE priority CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE project_sale_type CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE status CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');

        $this->addSql('ALTER TABLE task CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE technology CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tracker_task_type CHANGE friendly_name friendly_name VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
