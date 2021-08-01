<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210801000417 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agence ADD compte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA9F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19AA9F2C56620 ON agence (compte_id)');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF6526046032730');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260D725330D');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260F225EBC4');
        $this->addSql('DROP INDEX IDX_CFF65260F225EBC4 ON compte');
        $this->addSql('DROP INDEX IDX_CFF6526046032730 ON compte');
        $this->addSql('DROP INDEX IDX_CFF65260D725330D ON compte');
        $this->addSql('ALTER TABLE compte DROP user_compte_id, DROP type_compte_id, DROP agence_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA9F2C56620');
        $this->addSql('DROP INDEX UNIQ_64C19AA9F2C56620 ON agence');
        $this->addSql('ALTER TABLE agence DROP compte_id');
        $this->addSql('ALTER TABLE compte ADD user_compte_id INT DEFAULT NULL, ADD type_compte_id INT DEFAULT NULL, ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF6526046032730 FOREIGN KEY (type_compte_id) REFERENCES type_compte (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260F225EBC4 FOREIGN KEY (user_compte_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFF65260F225EBC4 ON compte (user_compte_id)');
        $this->addSql('CREATE INDEX IDX_CFF6526046032730 ON compte (type_compte_id)');
        $this->addSql('CREATE INDEX IDX_CFF65260D725330D ON compte (agence_id)');
    }
}
