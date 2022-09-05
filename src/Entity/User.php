<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM; // permet d utiliser le namespace ORM evite d utilisr plein de use
use DateTime;

// info de mapping pour doctrine par attribut https://www.doctrine-project.org/projects/doctrine-orm/en/2.13/reference/attributes-reference.html


#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
#[ORM\Table(name: "users")]


/* qd la classe (entité) et le mapping et crée on valide:
`php vendor/bin/doctrine orm:validate-schema`

on met à jour la BDD:
`php vendor/bin/doctrine orm:schema-tool:update --dump-sql`

normalement ds phpmyadmin on a une nouvelle table crée par le mapping de la nouvelle classe
*/


class User
{
  #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "AUTO")] // mapping attributes PHP8 mini
  private int $id;

  #[ORM\Column(type: "string", length: 255)]
  private string $name;

  #[ORM\Column(type: "string", length: 255)]
  private string $firstname;

  #[ORM\Column(type: "string", length: 255)]
  private string $username;

  #[ORM\Column(type: "string", length: 255)]
  private string $password;

  #[ORM\Column(type: "string", length: 255)]
  private string $email;

  #[ORM\Column(type: "datetime", nullable: true)]
  private DateTime $birthDate;


// il y a une autre méthode a connaître: annotations utilisée avant PHP8 ex:

/** @Entity */

/**
 * @ORM\Entity
 * @ORM\Table(name="xxxx')
 * 
 */

 /** @Column(type="integer") */

 
  // ----- getters and setters 
  public function getId(): int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getFirstname(): string
  {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): self
  {
    $this->firstname = $firstname;

    return $this;
  }

  public function getUsername(): string
  {
    return $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getBirthDate(): DateTime
  {
    return $this->birthDate;
  }

  public function setBirthDate(DateTime $birthDate): self
  {
    $this->birthDate = $birthDate;

    return $this;
  }
}
