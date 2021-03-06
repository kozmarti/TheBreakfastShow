<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210620164030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_DDAA1CDA621F03B0 ON episode');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DDAA1CDA2B36786B ON episode (title)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_DDAA1CDA2B36786B ON episode');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DDAA1CDA621F03B0 ON episode (recipename)');
    }
}
