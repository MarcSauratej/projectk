<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserQuiz::class, orphanRemoval: true)]
    private $userQuizzes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserReward::class, orphanRemoval: true)]
    private $userRewards;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $avatar;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $deactivated;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    public function __construct()
    {
        $this->userQuizzes = new ArrayCollection();
        $this->userRewards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function isRewarded($reward): bool
    {
        foreach($this->userQuizzes as $userQuiz){
            if($userQuiz->getStatus() == 'completed' and $reward->getQuiz()->getId() == $userQuiz->getQuiz()->getId() ){
                return true;
            }
        }
        return false;
    }
    
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, UserQuiz>
     */
    public function getUserQuizzes(): Collection
    {
        return $this->userQuizzes;
    }

    public function addUserQuiz(UserQuiz $userQuiz): self
    {
        if (!$this->userQuizzes->contains($userQuiz)) {
            $this->userQuizzes[] = $userQuiz;
            $userQuiz->setUser($this);
        }

        return $this;
    }

    public function removeUserQuiz(UserQuiz $userQuiz): self
    {
        if ($this->userQuizzes->removeElement($userQuiz)) {
            // set the owning side to null (unless already changed)
            if ($userQuiz->getUser() === $this) {
                $userQuiz->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserReward>
     */
    public function getUserRewards(): Collection
    {
        return $this->userRewards;
    }

    public function addUserReward(UserReward $userReward): self
    {
        if (!$this->userRewards->contains($userReward)) {
            $this->userRewards[] = $userReward;
            $userReward->setUser($this);
        }

        return $this;
    }

    public function removeUserReward(UserReward $userReward): self
    {
        if ($this->userRewards->removeElement($userReward)) {
            // set the owning side to null (unless already changed)
            if ($userReward->getUser() === $this) {
                $userReward->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function isDeactivated(): ?bool
    {
        return $this->deactivated;
    }

    public function setDeactivated(?bool $deactivated): self
    {
        $this->deactivated = $deactivated;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
