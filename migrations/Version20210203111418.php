<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203111418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE list_ingredient (id INT AUTO_INCREMENT NOT NULL, measure_id INT DEFAULT NULL, ingredient_id INT NOT NULL, episode_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, sub_ingredient VARCHAR(255) DEFAULT NULL, INDEX IDX_B1820CAC5DA37D00 (measure_id), INDEX IDX_B1820CAC933FE08C (ingredient_id), INDEX IDX_B1820CAC362B62A0 (episode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE list_ingredient ADD CONSTRAINT FK_B1820CAC5DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id)');
        $this->addSql('ALTER TABLE list_ingredient ADD CONSTRAINT FK_B1820CAC933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE list_ingredient ADD CONSTRAINT FK_B1820CAC362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE list_ingredient');
    }
}
