<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210819231242 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE depot ADD retrait_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC7EF8457A FOREIGN KEY (retrait_id) REFERENCES retrait (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47948BBC7EF8457A ON depot (retrait_id)');
        $this->addSql('ALTER TABLE retrait DROP FOREIGN KEY FK_D9846A518510D4DE');
        $this->addSql('DROP INDEX IDX_D9846A518510D4DE ON retrait');
        $this->addSql('ALTER TABLE retrait DROP depot_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBC7EF8457A');
        $this->addSql('DROP INDEX UNIQ_47948BBC7EF8457A ON depot');
        $this->addSql('ALTER TABLE depot DROP retrait_id');
        $this->addSql('ALTER TABLE retrait ADD depot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE retrait ADD CONSTRAINT FK_D9846A518510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id)');
        $this->addSql('CREATE INDEX IDX_D9846A518510D4DE ON retrait (depot_id)');
    }
}
