<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211229080500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Populate task activity design types WITCHER-276';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095ACF60E67C');
        $this->addSql('DROP INDEX IDX_activity_owner ON activity');
        $this->addSql('ALTER TABLE activity CHANGE owner creator INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095ABC06EA63 FOREIGN KEY (creator) REFERENCES witcher_user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_activity_creator ON activity (creator)');
    }

    public function postUp(Schema $schema): void
    {
        $query = <<<SQL
            INSERT IGNORE INTO activity_type(friendly_name, handle, display_order) VALUES
                ('UI/UX Design', 'DESIGN', 6);
            SQL;

        $this->connection->executeQuery($query);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095ABC06EA63');
        $this->addSql('DROP INDEX IDX_activity_creator ON activity');
        $this->addSql('ALTER TABLE activity CHANGE creator owner INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095ACF60E67C FOREIGN KEY (owner) REFERENCES witcher_user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_activity_owner ON activity (owner)');
    }
}
