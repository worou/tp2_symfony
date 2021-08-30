<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields={"email"},
 * message="Ce compte exite déjà."
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     * message = "Le champ '{{ label }}' n'est pas {{ value }}!")
     * @Assert\Length(
     *      min = 2,
     *      max = 12,
     *      minMessage = "Le champ username doit contenir au moins {{ limit }} charactères.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 12,
     *      minMessage = "Le champ mot de passe doit contenir au moins {{ limit }} charactères.")
     */
    private $password;

    /**
     *@Assert\Length(
     *      min = 2,
     *      max = 12,
     *      minMessage = "Le champ mot de passe doit contenir au moins {{ limit }} charactères.")
     *
     *  @Assert\EqualTo(propertyPath="password", message="Les mots de passe ne correspondent pas")
     */
    private $confirmPassword;
    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of confirmPassword
     */ 
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * Set the value of confirmPassword
     *
     * @return  self
     */ 
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function eraseCredentials()
    {
        
    }

    public function getSalt(){

    }

    public function getRoles(){
        return ['ROLE_USER'];
    }
    
    public function getUserIdentifier()
    {
        
    }
}
