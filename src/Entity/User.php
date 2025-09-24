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
#[UniqueEntity(fields: ['mail_pro'], message: 'Cet email professionnel est déjà utilisé.')]
#[UniqueEntity(fields: ['mail_perso'], message: 'Cet email personnel est déjà utilisé.')]
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

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private string $nom;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private string $prenom;

    #[ORM\Column(length: 180, nullable: true, unique: true)]
    #[Assert\Email]
    private ?string $mail_pro = null;

    #[ORM\Column(length: 180, nullable: true, unique: true)]
    #[Assert\Email]
    private ?string $mail_perso = null;

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

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $derniereConnexion = null;

    #[ORM\ManyToMany(targetEntity: Team::class, mappedBy: 'membres')]
    private Collection $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getIdentifiant(): string { return $this->identifiant; }
    public function setIdentifiant(string $identifiant): self { $this->identifiant = $identifiant; return $this; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }

    public function getMailPro(): ?string { return $this->mail_pro; }
    public function setMailPro(?string $mail_pro): self { $this->mail_pro = $mail_pro; return $this; }

    public function getMailPerso(): ?string { return $this->mail_perso; }
    public function setMailPerso(?string $mail_perso): self { $this->mail_perso = $mail_perso; return $this; }

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

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    public function getDerniereConnexion(): ?\DateTimeImmutable { return $this->derniereConnexion; }
    public function setDerniereConnexion(?\DateTimeImmutable $date): self { $this->derniereConnexion = $date; return $this; }

    /**
     * ✅ Obligatoire pour PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    /**
     * ✅ Obligatoire pour UserInterface (Symfony 6+)
     * Ici on utilise l'identifiant comme username
     */
    public function getUserIdentifier(): string
    {
        return $this->identifiant;
    }

    public function eraseCredentials(): void
    {
        // S'il y avait des données sensibles en clair, on les nettoierait ici
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addMembre($this);
        }
        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            $team->removeMembre($this);
        }
        return $this;
    }
}
