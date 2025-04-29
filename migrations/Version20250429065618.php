<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429065618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE credit_card_manual_edits ADD logo_url VARCHAR(255) NOT NULL, ADD deep_link VARCHAR(500) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE credit_cards CHANGE bank_id bank_id INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE credit_cards CHANGE bank_id bank_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE credit_card_manual_edits DROP logo_url, DROP deep_link
        SQL);
    }
}
