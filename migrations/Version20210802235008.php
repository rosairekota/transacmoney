<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210802235008 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, credit_amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, credit_code VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_1CC16EFE9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE debit (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, debit_code VARCHAR(255) NOT NULL, request_date DATETIME NOT NULL, status TINYINT(1) NOT NULL, debit_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE9B6B5FBA FOREIGN KEY (account_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE operation ADD code_operation VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE credit');
        $this->addSql('DROP TABLE debit');
        $this->addSql('ALTER TABLE operation DROP code_operation');
    }
}
