<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180828154158 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_answer_vote MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE user_answer_vote DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_answer_vote DROP id');
        $this->addSql('ALTER TABLE user_answer_vote ADD PRIMARY KEY (user_id, answer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_answer_vote DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_answer_vote ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user_answer_vote ADD PRIMARY KEY (id)');
    }
}
