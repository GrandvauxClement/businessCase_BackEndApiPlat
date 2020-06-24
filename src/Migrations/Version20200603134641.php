<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603134641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, garages_id INT DEFAULT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, carburant VARCHAR(255) NOT NULL, annee INT NOT NULL, kilometrage VARCHAR(255) NOT NULL, prix INT NOT NULL, date_ajout DATE NOT NULL, INDEX IDX_773DE69DFCB4E7AB (garages_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garages (id INT AUTO_INCREMENT NOT NULL, pro_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, rue VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, num_telephone VARCHAR(255) NOT NULL, INDEX IDX_8C4330E2C3B7E4BA (pro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, relation_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6A3256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pro (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, num_telephone VARCHAR(255) NOT NULL, num_siret VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6BB4D6FFE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DFCB4E7AB FOREIGN KEY (garages_id) REFERENCES garages (id)');
        $this->addSql('ALTER TABLE garages ADD CONSTRAINT FK_8C4330E2C3B7E4BA FOREIGN KEY (pro_id) REFERENCES pro (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A3256915B FOREIGN KEY (relation_id) REFERENCES car (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A3256915B');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DFCB4E7AB');
        $this->addSql('ALTER TABLE garages DROP FOREIGN KEY FK_8C4330E2C3B7E4BA');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE garages');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE pro');
    }
}
