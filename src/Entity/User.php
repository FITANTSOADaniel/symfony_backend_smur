<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['mailPro'], message: 'Cet email professionnel est déjà utilisé.')]
#[UniqueEntity(fields: ['mailPerso'], message: 'Cet email personnel est déjà utilisé.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $identifiant;

    #[ORM\Column(type: 'integer', unique: true)]
    #[Assert\NotBlank]
    private ?int $numero = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private string $nom;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private string $prenom;

    #[ORM\Column(length: 180, nullable: true, unique: true)]
    #[Assert\Email]
    private ?string $mailPro;

    #[ORM\Column(length: 180, nullable: true, unique: true)]
    #[Assert\Email]
    private ?string $mailPerso;

    #[ORM\Column(length: 255)]
    private string $motDePasse;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Regex(pattern: '/^\d{10}$/', message: 'Le téléphone doit contenir 10 chiffres.')]
    private ?string $telephone = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $fonction = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $metier = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $bureau = null;

    #[ORM\Column(type: 'boolean')]
    private bool $accesUser = false;

    #[ORM\Column(type: 'boolean')]
    private bool $accesTeam = false;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $derniereConnexion = null;

    public function getId(): ?int { return $this->id; }

    public function getIdentifiant(): string { return $this->identifiant; }
    public function setIdentifiant(string $identifiant): self { $this->identifiant = $identifiant; return $this; }

    public function getNumero(): ?int{ return $this->numero;}
    public function setNumero(int $numero): self{ $this->numero = $numero;return $this;}

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }

    public function getMailPro(): ?string { return $this->mailPro; }
    public function setMailPro(?string $mailPro): self { $this->mailPro = $mailPro; return $this; }

    public function getMailPerso(): ?string { return $this->mailPerso; }
    public function setMailPerso(?string $mailPerso): self { $this->mailPerso = $mailPerso; return $this; }

    public function getMotDePasse(): string { return $this->motDePasse; }
    public function setMotDePasse(string $motDePasse): self { $this->motDePasse = $motDePasse; return $this; }

    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $telephone): self { $this->telephone = $telephone; return $this; }

    public function getFonction(): ?string { return $this->fonction; }
    public function setFonction(?string $fonction): self { $this->fonction = $fonction; return $this; }

    public function getMetier(): ?string { return $this->metier; }
    public function setMetier(?string $metier): self { $this->metier = $metier; return $this; }

    public function getBureau(): ?string { return $this->bureau; }
    public function setBureau(?string $bureau): self { $this->bureau = $bureau; return $this; }

    public function getDerniereConnexion(): ?\DateTimeImmutable { return $this->derniereConnexion; }
    public function setDerniereConnexion(?\DateTimeImmutable $date): self { $this->derniereConnexion = $date; return $this; }

    public function getAccesUser(): bool { return $this->accesUser; }
    public function setAccesUser(bool $accesUser): self { $this->accesUser = $accesUser; return $this; }

    public function getAccesTeam(): bool { return $this->accesTeam; }
    public function setAccesTeam(bool $accesTeam): self { $this->accesTeam = $accesTeam; return $this; }

    public function getPassword(): string{ return $this->motDePasse; }
    public function getUserIdentifier(): string { return $this->identifiant; }

    public function eraseCredentials(): void
    {
        // S'il y avait des données sensibles en clair, on les nettoierait ici
    }
}
