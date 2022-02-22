<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220231500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(180) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE article ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN article.id IS NULL');
        $this->addSql('ALTER TABLE comment ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE comment ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN comment.id IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE article ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN article.id IS \'(DC2Type:comment_id)\'');
        $this->addSql('ALTER TABLE comment ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE comment ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN comment.id IS \'(DC2Type:comment_id)\'');
    }
}
