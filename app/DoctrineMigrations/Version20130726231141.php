<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130726231141 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE acme_content (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(1000) NOT NULL, description VARCHAR(140) NOT NULL, body LONGTEXT NOT NULL, type VARCHAR(140) NOT NULL, absolute_path_flag TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE acme_content_audit (id INT NOT NULL, rev INT NOT NULL, title VARCHAR(1000) DEFAULT NULL, description VARCHAR(140) DEFAULT NULL, body LONGTEXT DEFAULT NULL, type VARCHAR(140) DEFAULT NULL, absolute_path_flag TINYINT(1) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE acme_content");
        $this->addSql("DROP TABLE acme_content_audit");
    }
}
