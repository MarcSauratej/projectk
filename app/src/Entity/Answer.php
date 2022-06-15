<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $answer;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private $question;

    #[ORM\Column(type: 'boolean')]
    private $iscorrect;

    #[ORM\OneToMany(mappedBy: 'answer', targetEntity: UserResponse::class, orphanRemoval: true)]
    private $userResponses;

    public function __construct()
    {
        $this->userResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function isCorrect(): ?bool
    {
        return $this->iscorrect;
    }

    public function setIsCorrect(bool $iscorrect): self
    {
        $this->iscorrect = $iscorrect;

        return $this;
    }

    /**
     * @return Collection<int, UserResponse>
     */
    public function getUserResponses(): Collection
    {
        return $this->userResponses;
    }

    public function addUserResponse(UserResponse $userResponse): self
    {
        if (!$this->userResponses->contains($userResponse)) {
            $this->userResponses[] = $userResponse;
            $userResponse->setAnswer($this);
        }

        return $this;
    }

    public function removeUserResponse(UserResponse $userResponse): self
    {
        if ($this->userResponses->removeElement($userResponse)) {
            // set the owning side to null (unless already changed)
            if ($userResponse->getAnswer() === $this) {
                $userResponse->setAnswer(null);
            }
        }

        return $this;
    }
}
