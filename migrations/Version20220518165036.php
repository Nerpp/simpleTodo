<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518165036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_CB0048D46C6E55B5 ON listing');
        $this->addSql('ALTER TABLE listing CHANGE nom name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CB0048D45E237E06 ON listing (name)');
        $this->addSql('ALTER TABLE task CHANGE titre title VARCHAR(255) NOT NULL, CHANGE etat state TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_CB0048D45E237E06 ON listing');
        $this->addSql('ALTER TABLE listing CHANGE name nom VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CB0048D46C6E55B5 ON listing (nom)');
        $this->addSql('ALTER TABLE task CHANGE title titre VARCHAR(255) NOT NULL, CHANGE state etat TINYINT(1) DEFAULT NULL');
    }
}
