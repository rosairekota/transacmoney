<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210819205516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte DROP INDEX UNIQ_CFF65260A76ED395, ADD INDEX IDX_CFF65260A76ED395 (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499B6B5FBA');
        $this->addSql('DROP INDEX UNIQ_8D93D6499B6B5FBA ON user');
        $this->addSql('ALTER TABLE user DROP account_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte DROP INDEX IDX_CFF65260A76ED395, ADD UNIQUE INDEX UNIQ_CFF65260A76ED395 (user_id)');
        $this->addSql('ALTER TABLE user ADD account_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499B6B5FBA FOREIGN KEY (account_id) REFERENCES compte (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499B6B5FBA ON user (account_id)');
    }
}
