<?php

namespace App\Entity;

use App\Repository\UserResponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserResponseRepository::class)]
class UserResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: UserQuiz::class, inversedBy: 'userResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_quiz;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'userResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private $question;

    #[ORM\ManyToOne(targetEntity: Answer::class, inversedBy: 'userResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserQuiz(): ?UserQuiz
    {
        return $this->user_quiz;
    }

    public function setUserQuiz(?UserQuiz $user_quiz): self
    {
        $this->user_quiz = $user_quiz;

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

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
