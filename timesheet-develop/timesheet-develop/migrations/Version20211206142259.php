<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206142259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tracker_task_type to task table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2575F24961');
        $this->addSql('DROP INDEX IDX_task_witcherProjectTrackerTaskType ON task');
        $this->addSql('ALTER TABLE task CHANGE witcher_project_tracker_task_type tracker_task_type INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB252312256C FOREIGN KEY (tracker_task_type) REFERENCES tracker_task_type (id)');
        $this->addSql('CREATE INDEX IDX_task_trackerTaskType ON task (tracker_task_type)');
        $this->addSql('DROP INDEX IDX_tracker_task_type_displayOrder ON tracker_task_type');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F249612312256C');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F2496170D1B332');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249612312256C FOREIGN KEY (tracker_task_type) REFERENCES tracker_task_type (id)');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F2496170D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB252312256C');
        $this->addSql('DROP INDEX IDX_task_trackerTaskType ON task');
        $this->addSql('ALTER TABLE task CHANGE tracker_task_type witcher_project_tracker_task_type INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2575F24961 FOREIGN KEY (witcher_project_tracker_task_type) REFERENCES witcher_project_tracker_task_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_task_witcherProjectTrackerTaskType ON task (witcher_project_tracker_task_type)');
        $this->addSql('CREATE INDEX IDX_tracker_task_type_displayOrder ON tracker_task_type (display_order)');

        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F2496170D1B332');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type DROP FOREIGN KEY FK_75F249612312256C');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F2496170D1B332 FOREIGN KEY (witcher_project) REFERENCES witcher_project (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE witcher_project_tracker_task_type ADD CONSTRAINT FK_75F249612312256C FOREIGN KEY (tracker_task_type) REFERENCES tracker_task_type (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
