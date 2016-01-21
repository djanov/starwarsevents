<?php

namespace Yoda\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
* User
*
* @ORM\Table(name="yoda_user")
* @ORM\Entity(repositoryClass="Yoda\UserBundle\Repository\UserRepository")
*/
class User implements AdvancedUserInterface
{
  /**
  * @var int
  *
  * @ORM\Column(name="id", type="integer")
  * @ORM\Id
  * @ORM\GeneratedValue(strategy="AUTO")
  */
  private $id;

  /**
  * @var string
  *
  * @ORM\Column(name="username", type="string", length=255)
  */
  private $username;

  /**
  * @var string
  *
  * @ORM\Column(name="password", type="string", length=255)
  */
  private $password;

  /**
  * @var array
  *
  * @ORM\Column(name="roles", type="json_array")
  */
  private $roles = array();
  /**
  * @var bool
  *
  * @ORM\Column(name="is_active", type="boolean")
  */
  private $isActive = true;

  /**
  * Get id
  *
  * @return int
  */
  public function getId()
  {
    return $this->id;
  }

  /**
  * Set username
  *
  * @param string $username
  *
  * @return User
  */
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }

  /**
  * Get username
  *
  * @return string
  */
  public function getUsername()
  {
    return $this->username;
  }

  /**
  * Set password
  *
  * @param string $password
  *
  * @return User
  */
  public function setPassword($password)
  {
    $this->password = $password;

    return $this;
  }

  /**
  * Get password
  *
  * @return string
  */
  public function getPassword()
  {
    return $this->password;
  }

  public function getRoles() {
    $roles = $this->roles;
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles) {

    $this->roles = $roles;

    return $this;
  }

  public function eraseCredentials() {
    // logic goes here later
  }

  public function getSalt() {
    return null;
  }

  /**
  * @return boolean
  */
  public function getIsActive() {
    return $this->isActive;
  }

  /**
  * @param boolean $isActive
  */
  public function setIsActive($isActive) {
    $this->isActive = $isActive;
  }

  public function isAccountNonExpired()
  {
    return true;
  }

  public function isAccountNonLocked()
  {
    return true;
  }

  public function isCredentialsNonExpired()
  {
    return true;
  }

  public function isEnabled()
  {
    return $this->getIsActive();
  }
}
