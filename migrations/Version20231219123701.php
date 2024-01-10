<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219123701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entity (id INT AUTO_INCREMENT NOT NULL, auth_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, pub_date DATE NOT NULL, img_link VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E284468B366CF22 (auth_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entity ADD CONSTRAINT FK_E284468B366CF22 FOREIGN KEY (auth_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD author_id_id INT DEFAULT NULL, DROP auth_id, CHANGE pub_date pub_date DATE NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6669CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E6669CCBE9A ON article (author_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entity DROP FOREIGN KEY FK_E284468B366CF22');
        $this->addSql('DROP TABLE entity');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6669CCBE9A');
        $this->addSql('DROP INDEX UNIQ_23A0E6669CCBE9A ON article');
        $this->addSql('ALTER TABLE article ADD auth_id VARCHAR(255) NOT NULL, DROP author_id_id, CHANGE pub_date pub_date DATETIME NOT NULL');
    }
}
