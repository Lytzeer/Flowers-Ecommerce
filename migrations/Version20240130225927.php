<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240130225927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E662989F1FD');
        $this->addSql('DROP INDEX IDX_23A0E662989F1FD ON article');
        $this->addSql('ALTER TABLE article DROP invoice_id');
        $this->addSql('ALTER TABLE invoice ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517447294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_906517447294869C ON invoice (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD invoice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E662989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_23A0E662989F1FD ON article (invoice_id)');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517447294869C');
        $this->addSql('DROP INDEX IDX_906517447294869C ON invoice');
        $this->addSql('ALTER TABLE invoice DROP article_id');
    }
}
