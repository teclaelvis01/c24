<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418111422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration to create banks and credit_cards tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE banks (id INT AUTO_INCREMENT NOT NULL, external_bank_id INT NOT NULL, name VARCHAR(500) NOT NULL, UNIQUE INDEX UNIQ_AB063796C86A2202 (external_bank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE credit_cards (id INT AUTO_INCREMENT NOT NULL, bank_id INT DEFAULT NULL, external_product_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(10) NOT NULL, description VARCHAR(500) NOT NULL, logo_url VARCHAR(255) NOT NULL, deep_link VARCHAR(500) NOT NULL, extra_info VARCHAR(500) NOT NULL, first_year_fee_amount NUMERIC(10, 2) NOT NULL, first_year_fee_currency_code VARCHAR(3) NOT NULL, incentive_amount NUMERIC(10, 2) NOT NULL, incentive_currency_code VARCHAR(3) NOT NULL, cost_amount NUMERIC(10, 2) NOT NULL, cost_currency_code VARCHAR(3) NOT NULL, UNIQUE INDEX UNIQ_5CADD6535F7AE988 (external_product_id), INDEX IDX_5CADD65311C8FB41 (bank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE credit_cards ADD CONSTRAINT FK_5CADD65311C8FB41 FOREIGN KEY (bank_id) REFERENCES banks (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE credit_cards DROP FOREIGN KEY FK_5CADD65311C8FB41
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE banks
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE credit_cards
        SQL);
    }
}
