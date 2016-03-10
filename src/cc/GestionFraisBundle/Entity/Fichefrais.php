<?php

namespace cc\GestionFraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fichefrais
 *
 * @ORM\Table(name="FicheFrais", indexes={@ORM\Index(name="idEtat", columns={"idEtat"}), @ORM\Index(name="IDX_1C4987DC1D06ADE3", columns={"idVisiteur"})})
 * @ORM\Entity
 */
class Fichefrais
{
    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=6, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $mois;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbJustificatifs", type="integer", nullable=true)
     */
    private $nbjustificatifs;

    /**
     * @var string
     *
     * @ORM\Column(name="montantValide", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantvalide;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="date", nullable=true)
     */
    private $datemodif;

    /**
     * @var \Etat
     *
     * @ORM\ManyToOne(targetEntity="Etat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEtat", referencedColumnName="id")
     * })
     */
    private $idetat;

    /**
     * @var \Visiteur
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Visiteur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVisiteur", referencedColumnName="id")
     * })
     */
    private $idvisiteur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Fraisforfait", mappedBy="idvisiteur")
     */
    private $idfraisforfait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idfraisforfait = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set mois
     *
     * @param string $mois
     *
     * @return Fichefrais
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return string
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set nbjustificatifs
     *
     * @param integer $nbjustificatifs
     *
     * @return Fichefrais
     */
    public function setNbjustificatifs($nbjustificatifs)
    {
        $this->nbjustificatifs = $nbjustificatifs;

        return $this;
    }

    /**
     * Get nbjustificatifs
     *
     * @return integer
     */
    public function getNbjustificatifs()
    {
        return $this->nbjustificatifs;
    }

    /**
     * Set montantvalide
     *
     * @param string $montantvalide
     *
     * @return Fichefrais
     */
    public function setMontantvalide($montantvalide)
    {
        $this->montantvalide = $montantvalide;

        return $this;
    }

    /**
     * Get montantvalide
     *
     * @return string
     */
    public function getMontantvalide()
    {
        return $this->montantvalide;
    }

    /**
     * Set datemodif
     *
     * @param \DateTime $datemodif
     *
     * @return Fichefrais
     */
    public function setDatemodif($datemodif)
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    /**
     * Get datemodif
     *
     * @return \DateTime
     */
    public function getDatemodif()
    {
        return $this->datemodif;
    }

    /**
     * Set idetat
     *
     * @param \cc\GestionFraisBundle\Entity\Etat $idetat
     *
     * @return Fichefrais
     */
    public function setIdetat(\cc\GestionFraisBundle\Entity\Etat $idetat = null)
    {
        $this->idetat = $idetat;

        return $this;
    }

    /**
     * Get idetat
     *
     * @return \cc\GestionFraisBundle\Entity\Etat
     */
    public function getIdetat()
    {
        return $this->idetat;
    }

    /**
     * Set idvisiteur
     *
     * @param \cc\GestionFraisBundle\Entity\Visiteur $idvisiteur
     *
     * @return Fichefrais
     */
    public function setIdvisiteur(\cc\GestionFraisBundle\Entity\Visiteur $idvisiteur)
    {
        $this->idvisiteur = $idvisiteur;

        return $this;
    }

    /**
     * Get idvisiteur
     *
     * @return \cc\GestionFraisBundle\Entity\Visiteur
     */
    public function getIdvisiteur()
    {
        return $this->idvisiteur;
    }

    /**
     * Add idfraisforfait
     *
     * @param \cc\GestionFraisBundle\Entity\Fraisforfait $idfraisforfait
     *
     * @return Fichefrais
     */
    public function addIdfraisforfait(\cc\GestionFraisBundle\Entity\Fraisforfait $idfraisforfait)
    {
        $this->idfraisforfait[] = $idfraisforfait;

        return $this;
    }

    /**
     * Remove idfraisforfait
     *
     * @param \cc\GestionFraisBundle\Entity\Fraisforfait $idfraisforfait
     */
    public function removeIdfraisforfait(\cc\GestionFraisBundle\Entity\Fraisforfait $idfraisforfait)
    {
        $this->idfraisforfait->removeElement($idfraisforfait);
    }

    /**
     * Get idfraisforfait
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdfraisforfait()
    {
        return $this->idfraisforfait;
    }
}
