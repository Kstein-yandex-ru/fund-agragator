<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240618194158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fund_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_fund_categories (user_id INT NOT NULL, fund_categories_id INT NOT NULL, INDEX IDX_2679A534A76ED395 (user_id), INDEX IDX_2679A53413CEEF95 (fund_categories_id), PRIMARY KEY(user_id, fund_categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_fund_categories ADD CONSTRAINT FK_2679A534A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_fund_categories ADD CONSTRAINT FK_2679A53413CEEF95 FOREIGN KEY (fund_categories_id) REFERENCES fund_categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_fund_categories DROP FOREIGN KEY FK_2679A534A76ED395');
        $this->addSql('ALTER TABLE user_fund_categories DROP FOREIGN KEY FK_2679A53413CEEF95');
        $this->addSql('DROP TABLE fund_categories');
        $this->addSql('DROP TABLE user_fund_categories');
    }
}
