<?php

namespace cc\GestionFraisBundle\BaseDeDonnees\Services;

use PDO;
use Symfony\Component\HttpFoundation\Response;

/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

namespace cc\GestionFraisBundle\BaseDeDonnees\Services;

use PDO;

class Modele{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gestionfrais';   		
      	private static $user='root' ;    		
      	private static $mdp='mysql' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	public function __construct(){
    	Modele::$monPdo = new \PDO(Modele::$serveur.';'.Modele::$bdd, Modele::$user, Modele::$mdp, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
		Modele::$monPdo->query("SET CHARACTER SET utf8");
            /*
            $p = $this->container->getParameter('database.server');
            $pdo = new \PDO($p['dsn'], $p['username'], $p['password'], array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            $pdo->query("SET NAMES 'UTF8'");
            PdoGsb::$monPdo = $pdo;
}
             */
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
        /*
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
         */
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select Visiteur.id as id, Visiteur.nom as nom, Visiteur.prenom as prenom from Visiteur 
		where Visiteur.login='$login' and Visiteur.mdp='$mdp'";
		$rs = Modele::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

        
        public function getInfosComptable($login, $mdp){
            $req = "select Comptable.id as id, Comptable.nom as nom, Comptable.prenom as prenom "
                    . "from Comptable "
                    . "where Comptable.login='$login' and Comptable.mdp='$mdp'";
            $rs = Modele::$monPdo->query($req);
            $ligne = $rs->fetch();
            return $ligne;
        }
        
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from LigneFraisHorsForfait where LigneFraisHorsForfait.idvisiteur ='$idVisiteur' 
		and LigneFraisHorsForfait.mois = '$mois' ";	
		$res = Modele::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
                /*
		for ($i=0; $i<$nbLignes; $i++){
                    
                        
			list($annee,$mois,$jour) = explode("-",$date) ;
                        $dateFR = sprintf("%02d/%02S/%04d",$jour,$mois,$annee) ;
                        
                        $lesLignes[$i]['date'] = $dateFR ;
//			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
                */
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select FicheFrais.nbjustificatifs as nb from  FicheFrais where FicheFrais.idvisiteur ='$idVisiteur' and FicheFrais.mois = '$mois'";
		$res = Modele::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select FraisForfait.id as idfrais, FraisForfait.libelle as libelle, 
		LigneFraisForfait.quantite as quantite from LigneFraisForfait inner join FraisForfait 
		on FraisForfait.id = LigneFraisForfait.idfraisforfait
		where LigneFraisForfait.idvisiteur ='$idVisiteur' and LigneFraisForfait.mois='$mois' 
		order by LigneFraisForfait.idfraisforfait";
		$res = Modele::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select FraisForfait.id as idfrais from FraisForfait order by FraisForfait.id";
		$res = Modele::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update LigneFraisForfait set LigneFraisForfait.quantite = $qte
			where LigneFraisForfait.idvisiteur = '$idVisiteur' and LigneFraisForfait.mois = '$mois'
			and LigneFraisForfait.idfraisforfait = '$unIdFrais'";
			Modele::$monPdo->exec($req);
		}
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update FicheFrais set nbjustificatifs = $nbJustificatifs 
		where FicheFrais.idvisiteur = '$idVisiteur' and FicheFrais.mois = '$mois'";
		Modele::$monPdo->exec($req);	
	}
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from FicheFrais 
		where FicheFrais.mois = '$mois' and FicheFrais.idvisiteur = '$idVisiteur'";
		$res = Modele::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from FicheFrais where FicheFrais.idvisiteur = '$idVisiteur'";
		$res = Modele::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into FicheFrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		Modele::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into LigneFraisForfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			Modele::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creerNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		//$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into LigneFraisHorsForfait 
		values('','$idVisiteur','$mois','$libelle','$date','$montant')";
		Modele::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "update LigneFraisHorsForfait set libelle = concat('REFUSE',libelle) where LigneFraisHorsForfait.id =$idFrais ";
		Modele::$monPdo->exec($req);
	}
        
        public function annulerFraisHorsForfait($idFrais){
            $req = "delete from LigneFraisHorsForfait where id = ".$idFrais;
            Modele::$monPdo->exec($req);
        }
        
        public function majFraisHorsForfait($liste){
            $req = "";
            foreach ($liste as $frais){
                $req = "update LigneFraisHorsForfait set date=".$frais['date'].", libelle=".$frais['libelle'].
                        ", montant=".$frais['montant']." where id=".$frais[id];
                Modele::$monPdo->exec($req);
            }
        }
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select FicheFrais.mois as mois from  FicheFrais where FicheFrais.idvisiteur ='$idVisiteur' 
		order by FicheFrais.mois desc ";
		$res = Modele::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select FicheFrais.idVisiteur as idVis , FicheFrais.mois as mois, FicheFrais.idEtat as idEtat, FicheFrais.dateModif as dateModif, FicheFrais.nbJustificatifs as nbJustificatifs, 
			FicheFrais.montantValide as montantValide, Etat.libelle as libEtat from  FicheFrais inner join Etat on FicheFrais.idEtat = Etat.id 
			where FicheFrais.idvisiteur ='$idVisiteur' and FicheFrais.mois = '$mois'";
                
		$res = Modele::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update FicheFrais set idEtat = '$etat', dateModif = now() 
		where FicheFrais.idvisiteur ='$idVisiteur' and FicheFrais.mois = '$mois'";
		Modele::$monPdo->exec($req);
	}
        
       public function getListeVisiteurs(){
            $req = "select id, nom, prenom from Visiteur";
            $res = Modele::$monPdo->query($req);
            $lesLignes = $res->fetchAll();
	    return $lesLignes;
       }
       
       public function getFraisHorsForfait($idFraisH){
           $req = "select * from LigneFraisHorsForfait where id='$idFraisH'";
           $res = Modele::$monPdo->query($req);
           $laLigne = $res->fetch();
	   return $laLigne;
       }
       
       
       public function majMontantValide($idVisiteur, $mois, $montantValide){
           $req = "update FicheFrais set montantValide = $montantValide 
		where FicheFrais.idvisiteur = '$idVisiteur' and FicheFrais.mois = '$mois'";
	    Modele::$monPdo->exec($req);
       }
       
       public function getFichesValides(){
           $req = "select * from FicheFrais where idEtat='VA'";
           $res = Modele::$monPdo->query($req);
           $lesLignes = $res->fetchAll();
           return $lesLignes;
       }
       
       public function changerMoisFraisHorsForfait($id, $mois){
           $req = "update LigneFraisHorsForfait set mois = ".$mois." where id = ".$id." ";
           Modele::$monPdo->exec($req);
       }
}
?>
