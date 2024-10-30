<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029151908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD wish_id INT DEFAULT NULL, ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC42B83698 FOREIGN KEY (wish_id) REFERENCES wish (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC42B83698 ON commentaire (wish_id)');
        $this->addSql('CREATE INDEX IDX_67F068BCF675F31B ON commentaire (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC42B83698');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCF675F31B');
        $this->addSql('DROP INDEX IDX_67F068BC42B83698 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BCF675F31B ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP wish_id, DROP author_id');
    }
}
