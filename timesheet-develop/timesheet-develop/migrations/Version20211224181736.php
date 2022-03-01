<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211224181736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove history database structure WITCHER-195';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracker_history_change_log_item DROP FOREIGN KEY FK_A7985EBBCC188BEF');
        $this->addSql('DROP TABLE tracker_history_change_log');
        $this->addSql('DROP TABLE tracker_history_change_log_item');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tracker_history_change_log (id INT AUTO_INCREMENT NOT NULL, task INT NOT NULL, creator INT DEFAULT NULL, created_at DATETIME NOT NULL, external_id INT DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_tracker_history_change_log_creator (creator), INDEX IDX_tracker_history_change_log_task (task), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tracker_history_change_log_item (id INT AUTO_INCREMENT NOT NULL, tracker_history_change_log INT NOT NULL, field VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, field_type VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, change_from VARCHAR(2500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, change_to VARCHAR(2500) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_tracker_history_change_log_item_trackerHistoryChangeLog (tracker_history_change_log), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tracker_history_change_log ADD CONSTRAINT FK_CC188BEF527EDB25 FOREIGN KEY (task) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tracker_history_change_log ADD CONSTRAINT FK_CC188BEFBC06EA63 FOREIGN KEY (creator) REFERENCES witcher_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tracker_history_change_log_item ADD CONSTRAINT FK_A7985EBBCC188BEF FOREIGN KEY (tracker_history_change_log) REFERENCES tracker_history_change_log (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
