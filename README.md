symfony3
========

A Symfony project created on August 14, 2016, 6:34 pm.


UPDATE loonins 2.0   May 04, 2017 02:00 am
============

CREATE TABLE affect_login_neguit (id INT AUTO_INCREMENT NOT NULL, employe INT DEFAULT NULL, debutAffectation DATETIME NOT NULL, finAffectation DATE DEFAULT NULL, loginAnimNeguit INT DEFAULT NULL, INDEX IDX_DFA13042F804D3B9 (employe), INDEX IDX_DFA130427D88D068 (loginAnimNeguit), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE affect_fantome_neguit (id INT AUTO_INCREMENT NOT NULL, debutAffect DATE NOT NULL, finAffect DATE DEFAULT NULL, affectLogintNeguit INT DEFAULT NULL, profilVirtuel INT DEFAULT NULL, INDEX IDX_8E8C6EA6B12F9732 (affectLogintNeguit), INDEX IDX_8E8C6EA613A5AFFE (profilVirtuel), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE planning_conge (id INT AUTO_INCREMENT NOT NULL, source VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, del INT DEFAULT NULL, createdBy INT DEFAULT NULL, INDEX IDX_BF7429D7D3564642 (createdBy), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE planning_hebdo (id INT AUTO_INCREMENT NOT NULL, numWeek INT NOT NULL, mois INT NOT NULL, annee INT NOT NULL, source VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, del INT DEFAULT NULL, createdBy INT DEFAULT NULL, INDEX IDX_65FBBB1BD3564642 (createdBy), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE solde (id INT AUTO_INCREMENT NOT NULL, employe INT DEFAULT NULL, solde INT NOT NULL, derniereMaj DATETIME NOT NULL, typeDemande INT DEFAULT NULL, INDEX IDX_66918367F804D3B9 (employe), INDEX IDX_6691836733CD6282 (typeDemande), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE type_demande (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, del INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE demande_conge (id INT AUTO_INCREMENT NOT NULL, employe INT DEFAULT NULL, validateur INT DEFAULT NULL, debut DATE NOT NULL, fin DATE DEFAULT NULL, statut INT NOT NULL, del INT DEFAULT NULL, motif VARCHAR(255) NOT NULL, typeDemande INT DEFAULT NULL, INDEX IDX_D8061061F804D3B9 (employe), INDEX IDX_D8061061BB9A049E (validateur), INDEX IDX_D806106133CD6282 (typeDemande), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE affect_login_neguit ADD CONSTRAINT FK_DFA13042F804D3B9 FOREIGN KEY (employe) REFERENCES grh_employes (Id);
ALTER TABLE affect_login_neguit ADD CONSTRAINT FK_DFA130427D88D068 FOREIGN KEY (loginAnimNeguit) REFERENCES login_anim_neguit (id);
ALTER TABLE affect_fantome_neguit ADD CONSTRAINT FK_8E8C6EA6B12F9732 FOREIGN KEY (affectLogintNeguit) REFERENCES affect_login_neguit (id);
ALTER TABLE affect_fantome_neguit ADD CONSTRAINT FK_8E8C6EA613A5AFFE FOREIGN KEY (profilVirtuel) REFERENCES profil_virtuel (id);
ALTER TABLE planning_conge ADD CONSTRAINT FK_BF7429D7D3564642 FOREIGN KEY (createdBy) REFERENCES User (id);
ALTER TABLE planning_hebdo ADD CONSTRAINT FK_65FBBB1BD3564642 FOREIGN KEY (createdBy) REFERENCES User (id);
ALTER TABLE solde ADD CONSTRAINT FK_66918367F804D3B9 FOREIGN KEY (employe) REFERENCES grh_employes (Id);
ALTER TABLE solde ADD CONSTRAINT FK_6691836733CD6282 FOREIGN KEY (typeDemande) REFERENCES type_demande (id);
ALTER TABLE demande_conge ADD CONSTRAINT FK_D8061061F804D3B9 FOREIGN KEY (employe) REFERENCES grh_employes (Id);
ALTER TABLE demande_conge ADD CONSTRAINT FK_D8061061BB9A049E FOREIGN KEY (validateur) REFERENCES User (id);
ALTER TABLE demande_conge ADD CONSTRAINT FK_D806106133CD6282 FOREIGN KEY (typeDemande) REFERENCES type_demande (id);
ALTER TABLE grh_employes ADD user INT DEFAULT NULL;
ALTER TABLE grh_employes ADD CONSTRAINT FK_22E37D608D93D649 FOREIGN KEY (user) REFERENCES User (id);
CREATE UNIQUE INDEX UNIQ_22E37D602ABD43F2 ON grh_employes (Id);
CREATE UNIQUE INDEX UNIQ_22E37D608D93D649 ON grh_employes (user);
ALTER TABLE grh_contrats CHANGE fin_reel fin_reel DATE DEFAULT NULL;
ALTER TABLE TypeTable CHANGE code code VARCHAR(25) NOT NULL;
ALTER TABLE profil_virtuel CHANGE createdAt createdAt DATETIME NOT NULL;
ALTER TABLE login_anim_neguit CHANGE createdAt createdAt DATETIME NOT NULL;
ALTER TABLE type_anim_neguit CHANGE createdAt createdAt DATETIME NOT NULL;
ALTER TABLE planning_neguit DROP FOREIGN KEY FK_A5531511AA08CB10;
ALTER TABLE planning_neguit DROP FOREIGN KEY FK_A5531511DAFD8899;
ALTER TABLE planning_neguit CHANGE createdAt createdAt DATETIME NOT NULL;
ALTER TABLE planning_neguit ADD CONSTRAINT FK_A5531511AA08CB10 FOREIGN KEY (login) REFERENCES affect_login_neguit (id);
ALTER TABLE planning_neguit ADD CONSTRAINT FK_A5531511DAFD8899 FOREIGN KEY (fantome) REFERENCES affect_fantome_neguit (id);







19 Novembre 2017  Intranet  1.5.3
==========================================================
1- StatController->createSearchForm :
    Afficher la liste de toutes les animatrices dans lme formùulaire de recherche des stats du SAR. dateFinAffectaion IS NULL or dateFinAffection = '0000:00:00 00:00:00' 


UPDATE loonins 1.2.0   May 25, 2017 04:am
============
1 - RUN symfony schema update command from console ssh
2 - RUN SQL QUERY On MYSQl DATABASSE 
	INSERT INTO login_anim (`login` ) SELECT login FROM Animatrice
	UPDATE `login_anim` SET `dateCreation`= NOW() WHERE 1
3 - Run anim patch url http://localhost/symfony3-and-fos-user-bundle/web/app_dev.php/sar/patch/anim
4 - Remove login field and getter and setter from Animatrice Entity
5 - Add following line for the new login field in Animatrice Entity
    /**
     * @var \Loonins\GrhBundle\Entity\LoginAnim
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\LoginAnim")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="login", referencedColumnName="id")
     * })
    **/
    private $login;

6 - Run symfony command to generate acsessors in the Animatrice Entity
7 - Update the database schema running symfony command :  php bin/console doctrine:schema:update --force --dump-sql
8 - Set debutAffectation all Animatrice entities
9 - UPDATE   `Animatrice`  SET  `debutAffectation` = '2017-01-01'  WHERE  `debutAffectation` IS NULL
10 - UPDATE  `Animatrice`  SET  `finAffectation` = '2017-06-01'  WHERE  `del` = 1
11 - Run url http://localhost/symfony3-and-fos-user-bundle/web/app_dev.php/sar/patch/phone/number


