<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200302154148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE partenaire_contact (partenaire_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_539318EF98DE13AC (partenaire_id), INDEX IDX_539318EFE7A1254A (contact_id), PRIMARY KEY(partenaire_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partenaire_contact ADD CONSTRAINT FK_539318EF98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partenaire_contact ADD CONSTRAINT FK_539318EFE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partenaire ADD referent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partenaire ADD CONSTRAINT FK_32FFA37335E47E35 FOREIGN KEY (referent_id) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_32FFA37335E47E35 ON partenaire (referent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE partenaire_contact');
        $this->addSql('ALTER TABLE partenaire DROP FOREIGN KEY FK_32FFA37335E47E35');
        $this->addSql('DROP INDEX IDX_32FFA37335E47E35 ON partenaire');
        $this->addSql('ALTER TABLE partenaire DROP referent_id');
    }
}
