<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616145948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_comment DROP FOREIGN KEY FK_A99CE55F39C1776A');
        $this->addSql('ALTER TABLE post_comment ADD CONSTRAINT FK_A99CE55F39C1776A FOREIGN KEY (parent_post_id) REFERENCES post_comment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_comment DROP FOREIGN KEY FK_A99CE55F39C1776A');
        $this->addSql('ALTER TABLE post_comment ADD CONSTRAINT FK_A99CE55F39C1776A FOREIGN KEY (parent_post_id) REFERENCES post_comment (id)');
    }
}
