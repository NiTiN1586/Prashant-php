<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110182737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Define relation for rename tablename, columnname and indexname as per ticket WITCHER-161';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A527EDB25 FOREIGN KEY (task) REFERENCES task (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095ACF60E67C FOREIGN KEY (owner) REFERENCES witcher_user (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404555373C966 FOREIGN KEY (country) REFERENCES country (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455CDFA6CA0 FOREIGN KEY (industry) REFERENCES industry (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404556956883F FOREIGN KEY (currency) REFERENCES currency (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE git_branch ADD CONSTRAINT FK_D7B2FC08527EDB25 FOREIGN KEY (task) REFERENCES task (id)');

        $this->addSql('ALTER TABLE project_sale_type ADD CONSTRAINT FK_21AC661BE02654E5 FOREIGN KEY (business_branch) REFERENCES business_branch (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2570D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257C9DFC0C FOREIGN KEY (assignee) REFERENCES witcher_user (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2576D6E0F2 FOREIGN KEY (reporter) REFERENCES witcher_user (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25BC06EA63 FOREIGN KEY (creator) REFERENCES witcher_user (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257B00651C FOREIGN KEY (status) REFERENCES status (id)');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2562A6DC27 FOREIGN KEY (priority) REFERENCES priority (id)');

        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256F9DED91 FOREIGN KEY (parent_task) REFERENCES task (id)');

        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332E02654E5 FOREIGN KEY (business_branch) REFERENCES business_branch (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B3321DFF0ECA FOREIGN KEY (witcher_user) REFERENCES witcher_user (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332C7440455 FOREIGN KEY (client) REFERENCES client (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332309023CC FOREIGN KEY (sale_type) REFERENCES project_sale_type (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE witcher_user ADD CONSTRAINT FK_102CBBE35EBBF198 FOREIGN KEY (company_position) REFERENCES company_position (id)');

        $this->addSql('ALTER TABLE witcher_user ADD CONSTRAINT FK_102CBBE34D9192F8 FOREIGN KEY (supervisor) REFERENCES witcher_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE tblActivity ADD CONSTRAINT FK_7A198205AEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE tblActivity ADD CONSTRAINT FK_7A198205C04C9957 FOREIGN KEY (intOwnerId) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE tblClient ADD CONSTRAINT FK_E4B4A7E0345D687F FOREIGN KEY (intCurrencyId) REFERENCES ublCurrency (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblClient ADD CONSTRAINT FK_E4B4A7E065E37C67 FOREIGN KEY (intIndustryId) REFERENCES ublIndustry (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblClient ADD CONSTRAINT FK_E4B4A7E0AC743893 FOREIGN KEY (intCountryId) REFERENCES ublCountry (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblGitBranch ADD CONSTRAINT FK_FF62290FAEAE0B42 FOREIGN KEY (intTaskId) REFERENCES tblTask (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C2F9A3779 FOREIGN KEY (intParentTaskId) REFERENCES tblTask (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C3281966B FOREIGN KEY (intWitcherProjectId) REFERENCES tblWitcherProject (intId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C4F39F20C FOREIGN KEY (intStatusId) REFERENCES ublStatus (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C736452D1 FOREIGN KEY (intCreatorId) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C9603796 FOREIGN KEY (intReporterId) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45C975EADA1 FOREIGN KEY (intAssigneeId) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblTask ADD CONSTRAINT FK_4FBDB45CDA406E3B FOREIGN KEY (intPriorityId) REFERENCES ublPriority (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_57087355437EBCA FOREIGN KEY (intClientId) REFERENCES tblClient (intId) ON UPDATE NO ACTION ON DELETE CASCADE');

        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_570873563E1464C FOREIGN KEY (intSaleTypeId) REFERENCES ublProjectSaleType (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_57087357506BE97 FOREIGN KEY (intWictherUserId) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblWitcherProject ADD CONSTRAINT FK_5708735C096CAA3 FOREIGN KEY (intBusinessBranchId) REFERENCES ublBusinessBranch (intId) ON UPDATE NO ACTION ON DELETE SET NULL');

        $this->addSql('ALTER TABLE tblWitcherUser ADD CONSTRAINT FK_tblWitcherUser_company_position FOREIGN KEY (company_position) REFERENCES company_position (id) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE tblWitcherUser ADD CONSTRAINT FK_tblWitcher_supervisor FOREIGN KEY (supervisor) REFERENCES tblWitcherUser (intId) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE ublProjectSaleType ADD CONSTRAINT FK_7E750F0FC096CAA3 FOREIGN KEY (intBusinessBranchId) REFERENCES ublBusinessBranch (intId) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
