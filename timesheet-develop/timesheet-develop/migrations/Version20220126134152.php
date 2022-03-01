<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126134152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update cascade operations WITCHER-316';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2562A6DC27');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB256F9DED91');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2562A6DC27 FOREIGN KEY (priority) REFERENCES priority (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256F9DED91 FOREIGN KEY (parent_task) REFERENCES task (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332608531AB');
        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332C7440455');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332608531AB FOREIGN KEY (estimation_type) REFERENCES estimation_type (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332C7440455 FOREIGN KEY (client) REFERENCES client (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F249612312256C');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F2496170D1B332');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249612312256C FOREIGN KEY (tracker_task_type) REFERENCES tracker_task_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F2496170D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2562A6DC27');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB256F9DED91');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2562A6DC27 FOREIGN KEY (priority) REFERENCES priority (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256F9DED91 FOREIGN KEY (parent_task) REFERENCES task (id) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332C7440455');
        $this->addSql('ALTER TABLE witcher_project DROP FOREIGN KEY FK_70D1B332608531AB');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332C7440455 FOREIGN KEY (client) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE witcher_project ADD CONSTRAINT FK_70D1B332608531AB FOREIGN KEY (estimation_type) REFERENCES estimation_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F2496170D1B332');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F249612312256C');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F2496170D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249612312256C FOREIGN KEY (tracker_task_type) REFERENCES tracker_task_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
