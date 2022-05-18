<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ListingRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(
 * "name",
 *  message="Cette liste existe déjà.")
 */
#[ORM\Entity(repositoryClass: ListingRepository::class)]
class Listing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique:true)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'listing', targetEntity: Task::class, cascade:['persist'], orphanRemoval: true)]
    private $fkTask;

    public function __construct()
    {
        $this->fkTask = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getFkTask(): Collection
    {
        return $this->fkTask;
    }

    public function addFkTask(Task $fkTask): self
    {
        if (!$this->fkTask->contains($fkTask)) {
            $this->fkTask[] = $fkTask;
            $fkTask->setListing($this);
        }

        return $this;
    }

    public function removeFkTask(Task $fkTask): self
    {
        if ($this->fkTask->removeElement($fkTask)) {
            // set the owning side to null (unless already changed)
            if ($fkTask->getListing() === $this) {
                $fkTask->setListing(null);
            }
        }

        return $this;
    }
}
