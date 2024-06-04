<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603140316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_variant (id INT AUTO_INCREMENT NOT NULL, product_id_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, size DOUBLE PRECISION NOT NULL, INDEX IDX_209AA41DDE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41DDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE taxe ADD product_variant_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taxe ADD CONSTRAINT FK_56322FE925231DF8 FOREIGN KEY (product_variant_id_id) REFERENCES product_variant (id)');
        $this->addSql('CREATE INDEX IDX_56322FE925231DF8 ON taxe (product_variant_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taxe DROP FOREIGN KEY FK_56322FE925231DF8');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41DDE18E50B');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('DROP INDEX IDX_56322FE925231DF8 ON taxe');
        $this->addSql('ALTER TABLE taxe DROP product_variant_id_id');
    }
}
