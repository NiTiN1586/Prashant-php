<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201145351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update project_type WITCHER-351';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332309023CC');
        $this->addSql('CREATE TABLE project_type (id INT AUTO_INCREMENT NOT NULL, business_branch INT NOT NULL, friendly_name VARCHAR(255) NOT NULL, handle VARCHAR(30) NOT NULL, display_order INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_project_type_displayOrder (display_order), INDEX IDX_project_type_createdAt (created_at), INDEX IDX_project_type_deletedAt (deleted_at), INDEX IDX_project_type_businessBranch (business_branch), UNIQUE INDEX UK_project_type_handle (handle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_type ADD CONSTRAINT FK_B54F9F31E02654E5 FOREIGN KEY (business_branch) REFERENCES business_branch (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE project_sale_type');

        $this->addSql('DROP INDEX IDX_witcher_project_projectSaleType ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project CHANGE sale_type project_type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332B54F9F31 FOREIGN KEY (project_type) REFERENCES project_type (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_witcher_project_projectType ON witcher_project (project_type)');

        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332E02654E5');
        $this->addSql('DROP INDEX IDX_witcher_project_businessBranch ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project DROP business_branch');
    }

    public function postUp(Schema $schema): void
    {
        $businessBranchQuery = <<<SQL
            INSERT IGNORE INTO business_branch(friendly_name, handle, display_order, created_at, updated_at) VALUES
                ('Scaling', 'SCALING', 1, NOW(), NOW()),
                ('Factory', 'FACTORY', 2,  NOW(), NOW());
            SQL;

        $this->connection->executeQuery($businessBranchQuery);

        $businessBranches = [];

        foreach ($this->connection->executeQuery('SELECT id, handle FROM business_branch') as $businessBranch) {
            $businessBranches[$businessBranch['handle']] = $businessBranch['id'];
        }

        $projectTypeQuery = <<<SQL
            INSERT IGNORE INTO project_type(friendly_name, handle, business_branch, display_order, created_at, updated_at) VALUES
                ('Fixed', 'FIXED', :factory, 1, NOW(), NOW()),
                ('Packs', 'PACKS', :factory, 2, NOW(), NOW()),
                ('Material', 'MATERIAL', :scaling, 3, NOW(), NOW());
            SQL;

        $this->connection->executeQuery(
            $projectTypeQuery,
            [
                'factory' => $businessBranches['FACTORY'],
                'scaling' => $businessBranches['SCALING'],
            ],
            [
                'factory' => \PDO::PARAM_INT,
                'scaling' => \PDO::PARAM_INT,
            ]
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332B54F9F31');
        $this->addSql('CREATE TABLE project_sale_type (id INT AUTO_INCREMENT NOT NULL, business_branch INT NOT NULL, friendly_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, handle VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, display_order INT DEFAULT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_project_sale_type_businessBranch (business_branch), INDEX IDX_project_sale_type_createdAt (created_at), INDEX IDX_project_sale_type_deletedAt (deleted_at), INDEX IDX_project_sale_type_displayOrder (display_order), UNIQUE INDEX UK_project_sale_type_handle (handle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE project_sale_type ADD CONSTRAINT FK_21AC661BE02654E5 FOREIGN KEY (business_branch) REFERENCES business_branch (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE project_type');

        $this->addSql('DROP INDEX IDX_witcher_project_projectType ON witcher_project');
        $this->addSql('ALTER TABLE witcher_project CHANGE project_type sale_type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332309023CC FOREIGN KEY (sale_type) REFERENCES project_sale_type (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_witcher_project_projectSaleType ON witcher_project (sale_type)');

        $this->addSql('ALTER TABLE witcher_project ADD business_branch INT DEFAULT NULL');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332E02654E5 FOREIGN KEY (business_branch) REFERENCES business_branch (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_witcher_project_businessBranch ON witcher_project (business_branch)');
    }
}
