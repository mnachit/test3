<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230621132144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe_therapeutique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forme_galenique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gamme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboratory (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, forme_galenique_id INT NOT NULL, laboratory_id INT NOT NULL, gamme_id INT NOT NULL, sousgame_id INT NOT NULL, classe_therapeutique_id INT DEFAULT NULL, taxe_achat_id INT NOT NULL, taxe_vente_id INT NOT NULL, produit_tableau_id INT NOT NULL, name VARCHAR(255) NOT NULL, code_barre VARCHAR(255) NOT NULL, code_barre_2 VARCHAR(255) NOT NULL, ppv INT NOT NULL, need_prescription TINYINT(1) NOT NULL, market_product TINYINT(1) NOT NULL, base_remboursement VARCHAR(255) DEFAULT NULL, est_remboursable TINYINT(1) NOT NULL, date_created DATE DEFAULT NULL, date_modified DATE NOT NULL, statut VARCHAR(255) NOT NULL, pph VARCHAR(255) NOT NULL, category_margin VARCHAR(50) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04AD54FD80CB (forme_galenique_id), INDEX IDX_D34A04AD2F2A371E (laboratory_id), INDEX IDX_D34A04ADD2FD85F1 (gamme_id), INDEX IDX_D34A04ADCDA3CC71 (sousgame_id), INDEX IDX_D34A04ADAA64E93B (classe_therapeutique_id), INDEX IDX_D34A04AD83BD8CAC (taxe_achat_id), INDEX IDX_D34A04ADEF4AB1 (taxe_vente_id), INDEX IDX_D34A04AD195AECB1 (produit_tableau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_table (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sousgame (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxe_achat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxe_vente (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category_product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD54FD80CB FOREIGN KEY (forme_galenique_id) REFERENCES forme_galenique (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2F2A371E FOREIGN KEY (laboratory_id) REFERENCES laboratory (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD2FD85F1 FOREIGN KEY (gamme_id) REFERENCES gamme (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADCDA3CC71 FOREIGN KEY (sousgame_id) REFERENCES sousgame (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADAA64E93B FOREIGN KEY (classe_therapeutique_id) REFERENCES classe_therapeutique (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD83BD8CAC FOREIGN KEY (taxe_achat_id) REFERENCES taxe_achat (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADEF4AB1 FOREIGN KEY (taxe_vente_id) REFERENCES taxe_vente (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD195AECB1 FOREIGN KEY (produit_tableau_id) REFERENCES product_table (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD54FD80CB');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2F2A371E');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD2FD85F1');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADCDA3CC71');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADAA64E93B');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD83BD8CAC');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADEF4AB1');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD195AECB1');
        $this->addSql('DROP TABLE category_product');
        $this->addSql('DROP TABLE classe_therapeutique');
        $this->addSql('DROP TABLE forme_galenique');
        $this->addSql('DROP TABLE gamme');
        $this->addSql('DROP TABLE laboratory');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_table');
        $this->addSql('DROP TABLE sousgame');
        $this->addSql('DROP TABLE taxe_achat');
        $this->addSql('DROP TABLE taxe_vente');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
