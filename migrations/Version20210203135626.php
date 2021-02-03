<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203135626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fun_fact DROP FOREIGN KEY FK_BAB3B60933FE08C');
        $this->addSql('DROP INDEX UNIQ_BAB3B60933FE08C ON fun_fact');
        $this->addSql('ALTER TABLE fun_fact DROP ingredient_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fun_fact ADD ingredient_id INT NOT NULL');
        $this->addSql('ALTER TABLE fun_fact ADD CONSTRAINT FK_BAB3B60933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BAB3B60933FE08C ON fun_fact (ingredient_id)');
    }
}
