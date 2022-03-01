<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211220072806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create team functionality for Witcher project as per ticket WITCHER-221';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, witcher_project INT NOT NULL, team_leader INT DEFAULT NULL, name VARCHAR(200) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_team_witcherProject (witcher_project), INDEX IDX_team_teamLeader (team_leader), INDEX IDX_team_createdAt (created_at), INDEX IDX_team_updatedAt (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE witcher_user_team (team INT NOT NULL, witcher_user INT NOT NULL, INDEX IDX_47570F45C4E0A61F (team), INDEX IDX_47570F45102CBBE3 (witcher_user), PRIMARY KEY(team, witcher_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F70D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F83F8AD0E FOREIGN KEY (team_leader) REFERENCES witcher_user (id)');
        $this->addSql('ALTER TABLE witcher_user_team ADD CONSTRAINT FK_47570F45C4E0A61F FOREIGN KEY (team) REFERENCES team (id)');
        $this->addSql('ALTER TABLE witcher_user_team ADD CONSTRAINT FK_47570F45102CBBE3 FOREIGN KEY (witcher_user) REFERENCES witcher_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE witcher_user_team DROP FOREIGN KEY FK_47570F45C4E0A61F');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE witcher_user_team');
    }
}
