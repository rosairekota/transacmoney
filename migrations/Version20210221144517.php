<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210221144517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, abbrev_name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_64C19AA98BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beneficiaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, postnom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abbrev_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, user_compte_id INT DEFAULT NULL, montant_credit NUMERIC(10, 0) DEFAULT NULL, montant_debit NUMERIC(10, 0) DEFAULT NULL, solde NUMERIC(10, 0) DEFAULT NULL, INDEX IDX_CFF65260F225EBC4 (user_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT DEFAULT NULL, beneficiaire_id INT DEFAULT NULL, user_depot_id INT DEFAULT NULL, ville_id INT DEFAULT NULL, montant NUMERIC(10, 0) NOT NULL, code_depot VARCHAR(255) NOT NULL, date_depot DATETIME NOT NULL, montant_commission DOUBLE PRECISION NOT NULL, INDEX IDX_47948BBC10335F61 (expediteur_id), INDEX IDX_47948BBC5AF81F68 (beneficiaire_id), INDEX IDX_47948BBC659D30DE (user_depot_id), INDEX IDX_47948BBCA73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expediteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, postnom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retrait (id INT AUTO_INCREMENT NOT NULL, depot_id INT DEFAULT NULL, user_retrait_id INT DEFAULT NULL, montant_retire NUMERIC(10, 0) NOT NULL, montant_restant NUMERIC(10, 0) DEFAULT NULL, date_retrait DATETIME NOT NULL, beneficiaire_piece_type VARCHAR(255) NOT NULL, beneficiaire_piece_image VARCHAR(255) NOT NULL, beneficiaire_piece_numero VARCHAR(255) NOT NULL, libelle LONGTEXT DEFAULT NULL, INDEX IDX_D9846A518510D4DE (depot_id), INDEX IDX_D9846A51D99F8396 (user_retrait_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA98BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260F225EBC4 FOREIGN KEY (user_compte_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC10335F61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC5AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC659D30DE FOREIGN KEY (user_depot_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCA73F0036 FOREIGN KEY (ville_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE retrait ADD CONSTRAINT FK_D9846A518510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id)');
        $this->addSql('ALTER TABLE retrait ADD CONSTRAINT FK_D9846A51D99F8396 FOREIGN KEY (user_retrait_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D725330D ON user (agence_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D725330D');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBC5AF81F68');
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA98BAC62AF');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCA73F0036');
        $this->addSql('ALTER TABLE retrait DROP FOREIGN KEY FK_D9846A518510D4DE');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBC10335F61');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE beneficiaire');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE expediteur');
        $this->addSql('DROP TABLE retrait');
        $this->addSql('DROP INDEX IDX_8D93D649D725330D ON user');
        $this->addSql('ALTER TABLE user DROP agence_id');
    }
}
