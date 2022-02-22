<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221113012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE article ALTER author_id TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER author_id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN article.id IS NULL');
        $this->addSql('COMMENT ON COLUMN article.author_id IS NULL');
        $this->addSql('ALTER TABLE comment ADD article_id_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE comment DROP article_id');
        $this->addSql('ALTER TABLE comment ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE comment ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN comment.id IS NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526C8F3EC46 ON comment (article_id_id)');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN users.id IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:comment_id)\'');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C8F3EC46');
        $this->addSql('DROP INDEX IDX_9474526C8F3EC46');
        $this->addSql('ALTER TABLE comment ADD article_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment DROP article_id_id');
        $this->addSql('ALTER TABLE comment ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE comment ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN comment.id IS \'(DC2Type:comment_id)\'');
        $this->addSql('ALTER TABLE article ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE article ALTER author_id TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER author_id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN article.id IS \'(DC2Type:comment_id)\'');
        $this->addSql('COMMENT ON COLUMN article.author_id IS \'(DC2Type:comment_id)\'');
    }
}
