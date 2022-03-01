<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110183313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop old table because rename as per new code guideline in ticket WITCHER-161';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tblActivity');

        $this->addSql('DROP TABLE tblClient');

        $this->addSql('DROP TABLE tblGitBranch');

        $this->addSql('DROP TABLE tblTask');

        $this->addSql('DROP TABLE tblWitcherProject');

        $this->addSql('DROP TABLE tblWitcherUser');

        $this->addSql('DROP TABLE ublBusinessBranch');

        $this->addSql('DROP TABLE ublCountry');

        $this->addSql('DROP TABLE ublCurrency');

        $this->addSql('DROP TABLE ublIndustry');

        $this->addSql('DROP TABLE ublPriority');

        $this->addSql('DROP TABLE ublProjectSaleType');

        $this->addSql('DROP TABLE ublStatus');

        $this->addSql('DROP TABLE ublTechnology');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity');

        $this->addSql('DROP TABLE business_branch');

        $this->addSql('DROP TABLE client');

        $this->addSql('DROP TABLE country');

        $this->addSql('DROP TABLE currency');

        $this->addSql('DROP TABLE git_branch');

        $this->addSql('DROP TABLE industry');

        $this->addSql('DROP TABLE priority');

        $this->addSql('DROP TABLE project_sale_type');

        $this->addSql('DROP TABLE status');

        $this->addSql('DROP TABLE task');

        $this->addSql('DROP TABLE technology');

        $this->addSql('DROP TABLE witcher_project');

        $this->addSql('DROP TABLE witcher_user');
    }
}
