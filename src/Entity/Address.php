<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_address'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_address'])]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_address'])]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_address'])]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[Groups(['show_address'])]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'addressUsed', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setAddressUsed($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getAddressUsed() === $this) {
                $order->setAddressUsed(null);
            }
        }

        return $this;
    }
}
