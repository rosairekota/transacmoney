<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210613010939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE compte_t');
        $this->addSql('ALTER TABLE compte ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_CFF65260D725330D ON compte (agence_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE compte_t (id INT AUTO_INCREMENT NOT NULL, agence_id_id INT DEFAULT NULL, expediteur_id_id INT DEFAULT NULL, beneficiaire_id_id INT DEFAULT NULL, numero_compte VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_ouverture DATETIME NOT NULL, fin_validite DATETIME DEFAULT NULL, solde DOUBLE PRECISION DEFAULT NULL, INDEX IDX_CB0A72C8D1F6E7C3 (agence_id_id), INDEX IDX_CB0A72C8ADA744BA (expediteur_id_id), INDEX IDX_CB0A72C85EB92B42 (beneficiaire_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE compte_t ADD CONSTRAINT FK_CB0A72C85EB92B42 FOREIGN KEY (beneficiaire_id_id) REFERENCES beneficiaire (id)');
        $this->addSql('ALTER TABLE compte_t ADD CONSTRAINT FK_CB0A72C8ADA744BA FOREIGN KEY (expediteur_id_id) REFERENCES expediteur (id)');
        $this->addSql('ALTER TABLE compte_t ADD CONSTRAINT FK_CB0A72C8D1F6E7C3 FOREIGN KEY (agence_id_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260D725330D');
        $this->addSql('DROP INDEX IDX_CFF65260D725330D ON compte');
        $this->addSql('ALTER TABLE compte DROP agence_id');
    }
}
