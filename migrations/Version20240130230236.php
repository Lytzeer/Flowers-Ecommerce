<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240130230236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice_article (invoice_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F0E338B52989F1FD (invoice_id), INDEX IDX_F0E338B57294869C (article_id), PRIMARY KEY(invoice_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice_article ADD CONSTRAINT FK_F0E338B52989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoice_article ADD CONSTRAINT FK_F0E338B57294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517447294869C');
        $this->addSql('DROP INDEX IDX_906517447294869C ON invoice');
        $this->addSql('ALTER TABLE invoice DROP article_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_article DROP FOREIGN KEY FK_F0E338B52989F1FD');
        $this->addSql('ALTER TABLE invoice_article DROP FOREIGN KEY FK_F0E338B57294869C');
        $this->addSql('DROP TABLE invoice_article');
        $this->addSql('ALTER TABLE invoice ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517447294869C FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_906517447294869C ON invoice (article_id)');
    }
}
