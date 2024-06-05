<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604130131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF6154FFA');
        $this->addSql('DROP INDEX IDX_D34A04ADF6154FFA ON product');
        $this->addSql('ALTER TABLE product DROP product_image_id');
        $this->addSql('ALTER TABLE product_image ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_64617F034584665A ON product_image (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('DROP INDEX IDX_64617F034584665A ON product_image');
        $this->addSql('ALTER TABLE product_image DROP product_id');
        $this->addSql('ALTER TABLE product ADD product_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF6154FFA FOREIGN KEY (product_image_id) REFERENCES product_image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D34A04ADF6154FFA ON product (product_image_id)');
    }
}
