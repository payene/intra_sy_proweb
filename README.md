symfony3
========

A Symfony project created on August 14, 2016, 6:34 pm.

19 Novembre 2017  Intranet  2.0.3
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
