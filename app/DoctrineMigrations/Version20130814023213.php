<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130814023213 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE acme_content ADD parent_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE acme_content ADD CONSTRAINT FK_DC5653A7727ACA70 FOREIGN KEY (parent_id) REFERENCES acme_content (id) ON DELETE SET NULL");
        $this->addSql("CREATE INDEX IDX_DC5653A7727ACA70 ON acme_content (parent_id)");
        $this->addSql("ALTER TABLE acme_content_audit ADD parent_id INT DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE acme_content DROP FOREIGN KEY FK_DC5653A7727ACA70");
        $this->addSql("DROP INDEX IDX_DC5653A7727ACA70 ON acme_content");
        $this->addSql("ALTER TABLE acme_content DROP parent_id");
        $this->addSql("ALTER TABLE acme_content_audit DROP parent_id");
    }
}
