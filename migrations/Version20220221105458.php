<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221105458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD author_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE article ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN article.id IS NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_23A0E66F675F31B ON article (author_id)');
        $this->addSql('ALTER TABLE comment ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE comment ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN comment.id IS NULL');
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
        $this->addSql('ALTER TABLE article DROP CONSTRAINT FK_23A0E66F675F31B');
        $this->addSql('DROP INDEX IDX_23A0E66F675F31B');
        $this->addSql('ALTER TABLE article DROP author_id');
        $this->addSql('ALTER TABLE article ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN article.id IS \'(DC2Type:comment_id)\'');
        $this->addSql('ALTER TABLE comment ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE comment ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN comment.id IS \'(DC2Type:comment_id)\'');
    }
}
