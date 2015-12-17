<?php

namespace cc\GestionFraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comptable
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="cc\GestionFraisBundle\Entity\ComptableRepository")
 */
class Comptable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_comp", type="string", length=100)
     */
    private $nomComp;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_comp", type="string", length=100)
     */
    private $prenomComp;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=20)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=20)
     */
    private $mdp;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomComp
     *
     * @param string $nomComp
     *
     * @return Comptable
     */
    public function setNomComp($nomComp)
    {
        $this->nomComp = $nomComp;

        return $this;
    }

    /**
     * Get nomComp
     *
     * @return string
     */
    public function getNomComp()
    {
        return $this->nomComp;
    }

    /**
     * Set prenomComp
     *
     * @param string $prenomComp
     *
     * @return Comptable
     */
    public function setPrenomComp($prenomComp)
    {
        $this->prenomComp = $prenomComp;

        return $this;
    }

    /**
     * Get prenomComp
     *
     * @return string
     */
    public function getPrenomComp()
    {
        return $this->prenomComp;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Comptable
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set mdp
     *
     * @param string $mdp
     *
     * @return Comptable
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
    }
}

