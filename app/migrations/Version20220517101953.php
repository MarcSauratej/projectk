<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220517101953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_response (id INT AUTO_INCREMENT NOT NULL, user_quiz_id INT NOT NULL, question_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_DEF6EFFBDD31CF7F (user_quiz_id), INDEX IDX_DEF6EFFB1E27F6BF (question_id), INDEX IDX_DEF6EFFBAA334807 (answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_response ADD CONSTRAINT FK_DEF6EFFBDD31CF7F FOREIGN KEY (user_quiz_id) REFERENCES user_quiz (id)');
        $this->addSql('ALTER TABLE user_response ADD CONSTRAINT FK_DEF6EFFB1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE user_response ADD CONSTRAINT FK_DEF6EFFBAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_response');
    }
}
