<?php

namespace App\Entity;

use App\Repository\ChangePasswordRepository;
use Doctrine\ORM\Mapping as ORM;

class ChangePassword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $actual_password = null;

    #[ORM\Column(length: 255)]
    private ?string $new_password = null;

    #[ORM\Column(length: 255)]
    private ?string $repeat_new_password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActualPassword(): ?string
    {
        return $this->actual_password;
    }

    public function setActualPassword(string $actual_password): self
    {
        $this->actual_password = $actual_password;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->new_password;
    }

    public function setNewPassword(string $new_password): self
    {
        $this->new_password = $new_password;

        return $this;
    }

    public function getRepeatNewPassword(): ?string
    {
        return $this->repeat_new_password;
    }

    public function setRepeatNewPassword(string $repeat_new_password): self
    {
        $this->repeat_new_password = $repeat_new_password;

        return $this;
    }
}
