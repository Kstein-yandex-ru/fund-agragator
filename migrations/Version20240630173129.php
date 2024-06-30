<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630173129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, parrent_comment_id INT DEFAULT NULL, text LONGTEXT NOT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_5F9E962A166D1F9C (project_id), UNIQUE INDEX UNIQ_5F9E962A87AE8BA9 (parrent_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_rewards_default (project_id INT NOT NULL, rewards_default_id INT NOT NULL, INDEX IDX_786E2836166D1F9C (project_id), INDEX IDX_786E28367A240FE (rewards_default_id), PRIMARY KEY(project_id, rewards_default_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, user_id INT NOT NULL, user_to_id INT NOT NULL, text LONGTEXT DEFAULT NULL, rating INT NOT NULL, INDEX IDX_6970EB0F166D1F9C (project_id), UNIQUE INDEX UNIQ_6970EB0FA76ED395 (user_id), UNIQUE INDEX UNIQ_6970EB0FD2F7B13D (user_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A87AE8BA9 FOREIGN KEY (parrent_comment_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE project_rewards_default ADD CONSTRAINT FK_786E2836166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_rewards_default ADD CONSTRAINT FK_786E28367A240FE FOREIGN KEY (rewards_default_id) REFERENCES rewards_default (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FD2F7B13D FOREIGN KEY (user_to_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE project ADD rewards_individual_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE29FFC078 FOREIGN KEY (rewards_individual_id) REFERENCES rewards_individual (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE29FFC078 ON project (rewards_individual_id)');
        $this->addSql('ALTER TABLE project_categories ADD projects_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_categories ADD CONSTRAINT FK_22553D5A1EDE0F55 FOREIGN KEY (projects_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_22553D5A1EDE0F55 ON project_categories (projects_id)');
        $this->addSql('ALTER TABLE user ADD comments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64963379586 FOREIGN KEY (comments_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64963379586 ON user (comments_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64963379586');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A166D1F9C');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A87AE8BA9');
        $this->addSql('ALTER TABLE project_rewards_default DROP FOREIGN KEY FK_786E2836166D1F9C');
        $this->addSql('ALTER TABLE project_rewards_default DROP FOREIGN KEY FK_786E28367A240FE');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F166D1F9C');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FA76ED395');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FD2F7B13D');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE project_rewards_default');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE29FFC078');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE29FFC078 ON project');
        $this->addSql('ALTER TABLE project DROP rewards_individual_id');
        $this->addSql('ALTER TABLE project_categories DROP FOREIGN KEY FK_22553D5A1EDE0F55');
        $this->addSql('DROP INDEX IDX_22553D5A1EDE0F55 ON project_categories');
        $this->addSql('ALTER TABLE project_categories DROP projects_id');
        $this->addSql('DROP INDEX IDX_8D93D64963379586 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP comments_id');
    }
}
