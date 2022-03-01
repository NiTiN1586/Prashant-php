<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110092207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop foreign key from table for update tablename, columnname and indexname as per ticket WITCHER-161';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblWitcherProject DROP FOREIGN KEY FK_57087355437EBCA');

        $this->addSql('ALTER TABLE tblActivity DROP FOREIGN KEY FK_7A198205AEAE0B42');

        $this->addSql('ALTER TABLE tblGitBranch DROP FOREIGN KEY FK_FF62290FAEAE0B42');

        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C2F9A3779');

        $this->addSql('ALTER TABLE tracker_history_change_log DROP FOREIGN KEY FK_CC188BEFAEAE0B42');

        $this->addSql('ALTER TABLE git_project DROP FOREIGN KEY FK_AC0C61CE3281966B');

        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C3281966B');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F249613281966B');

        $this->addSql('ALTER TABLE tblActivity DROP FOREIGN KEY FK_7A198205C04C9957');

        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C736452D1');

        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C9603796');

        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C975EADA1');

        $this->addSql('ALTER TABLE tblWitcherProject DROP FOREIGN KEY FK_57087357506BE97');

        $this->addSql('ALTER TABLE tblWitcherUser DROP FOREIGN KEY FK_tblWitcher_supervisor');

        $this->addSql('ALTER TABLE tracker_history_change_log DROP FOREIGN KEY FK_CC188BEF736452D1');

        $this->addSql('ALTER TABLE witcher_user_department DROP FOREIGN KEY FK_witcher_user_department_witcher_user');

        $this->addSql('ALTER TABLE tblWitcherProject DROP FOREIGN KEY FK_5708735C096CAA3');

        $this->addSql('ALTER TABLE ublProjectSaleType DROP FOREIGN KEY FK_7E750F0FC096CAA3');

        $this->addSql('ALTER TABLE tblClient DROP FOREIGN KEY FK_E4B4A7E0AC743893');

        $this->addSql('ALTER TABLE tblClient DROP FOREIGN KEY FK_E4B4A7E0345D687F');

        $this->addSql('ALTER TABLE tblClient DROP FOREIGN KEY FK_E4B4A7E065E37C67');

        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45CDA406E3B');

        $this->addSql('ALTER TABLE tblWitcherProject DROP FOREIGN KEY FK_570873563E1464C');

        $this->addSql('ALTER TABLE tblTask DROP FOREIGN KEY FK_4FBDB45C4F39F20C');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_sale_type DROP FOREIGN KEY FK_21AC661BE02654E5');

        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332E02654E5');

        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332C7440455');

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404555373C966');

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404556956883F');

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455CDFA6CA0');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2562A6DC27');

        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332309023CC');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB257B00651C');

        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A527EDB25');

        $this->addSql('ALTER TABLE git_branch DROP FOREIGN KEY FK_D7B2FC08527EDB25');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB256F9DED91');

        $this->addSql('ALTER TABLE tracker_history_change_log DROP FOREIGN KEY FK_CC188BEF527EDB25');

        $this->addSql('ALTER TABLE git_project DROP FOREIGN KEY FK_AC0C61CE70D1B332');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2570D1B332');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F2496170D1B332');

        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095ACF60E67C');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB257C9DFC0C');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2576D6E0F2');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25BC06EA63');

        $this->addSql('ALTER TABLE tracker_history_change_log DROP FOREIGN KEY FK_CC188BEFBC06EA63');

        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B3321DFF0ECA');

        $this->addSql('ALTER TABLE witcher_user DROP FOREIGN KEY FK_102CBBE34D9192F8');

        $this->addSql('ALTER TABLE witcher_user_department DROP FOREIGN KEY FK_7EE9E406102CBBE3');
    }
}
