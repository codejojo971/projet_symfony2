<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200825074836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // etapes oiur ajouter une colonne NOT NULL et unique
        // sur une table avec des lignes déjà existantes

        
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        //1. Ajouter la colonnne en acceptant la valeur NULL 
        $this->addSql('ALTER TABLE user ADD pseudo VARCHAR(30) DEFAULT NULL');
        //2. Defenir une valeur à la nouvelle colonne pour toutes les lignes
        // la valeur va se baser sur la clé primaire pour etre unique
        $this->addSql('UPDATE user SET pseudo = CONCAT("user_", id) ');

        //3. Remettre la colonne en NOT NULL
        $this->addSql('ALTER TABLE user MODIFY pseudo VARCHAR(30) NOT NULL');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D64986CC499D ON user');
        $this->addSql('ALTER TABLE user DROP pseudo');
    }
}
