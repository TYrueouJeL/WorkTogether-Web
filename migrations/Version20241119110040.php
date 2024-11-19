<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119110040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commanded_unit CHANGE orders_id orders_id INT NOT NULL, CHANGE unit_id unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE intervention CHANGE type_id type_id INT NOT NULL, CHANGE unit_id unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE customer_id customer_id INT NOT NULL, CHANGE pack_id pack_id INT NOT NULL');
        $this->addSql('ALTER TABLE unit CHANGE bay_id bay_id INT NOT NULL, CHANGE usage_id usage_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD role LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commanded_unit CHANGE orders_id orders_id INT DEFAULT NULL, CHANGE unit_id unit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unit CHANGE bay_id bay_id INT DEFAULT NULL, CHANGE usage_id usage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE customer_id customer_id INT DEFAULT NULL, CHANGE pack_id pack_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervention CHANGE type_id type_id INT DEFAULT NULL, CHANGE unit_id unit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` DROP role');
    }
}
