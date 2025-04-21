<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418120641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE credit_card_manual_edits (id INT AUTO_INCREMENT NOT NULL, credit_card_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, incentive_amount_amount NUMERIC(10, 2) NOT NULL, incentive_amount_currency_code VARCHAR(3) NOT NULL, cost_amount NUMERIC(10, 2) NOT NULL, cost_currency_code VARCHAR(3) NOT NULL, INDEX IDX_E923F3057048FD0F (credit_card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE credit_card_manual_edits ADD CONSTRAINT FK_E923F3057048FD0F FOREIGN KEY (credit_card_id) REFERENCES credit_cards (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE credit_card_manual_edits DROP FOREIGN KEY FK_E923F3057048FD0F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE credit_card_manual_edits
        SQL);
    }
}
