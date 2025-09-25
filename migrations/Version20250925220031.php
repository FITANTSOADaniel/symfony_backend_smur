<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250925220031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, identifiant VARCHAR(180) NOT NULL, numero INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, mail_pro VARCHAR(180) DEFAULT NULL, mail_perso VARCHAR(180) DEFAULT NULL, mot_de_passe VARCHAR(255) NOT NULL, telephone VARCHAR(10) DEFAULT NULL, fonction VARCHAR(50) DEFAULT NULL, metier VARCHAR(50) DEFAULT NULL, bureau VARCHAR(50) DEFAULT NULL, acces_user TINYINT(1) NOT NULL, acces_team TINYINT(1) NOT NULL, derniere_connexion DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9C90409EC (identifiant), UNIQUE INDEX UNIQ_1483A5E9F55AE19E (numero), UNIQUE INDEX UNIQ_1483A5E977AFF995 (mail_pro), UNIQUE INDEX UNIQ_1483A5E989A1584E (mail_perso), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users');
    }
}
