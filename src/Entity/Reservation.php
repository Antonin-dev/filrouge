<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numberticket;

    /**
     * @ORM\Column(type="date")
     */
    private $datechoice;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ispaid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdat;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $AddressReservation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripesession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $qrcode;

    /**
     * @ORM\OneToOne(targetEntity=Ratings::class, mappedBy="reservation", cascade={"persist", "remove"})
     */
    private $ratings;

    public function __toString()
    {
        return $this->getDatechoice();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberticket(): ?string
    {
        return $this->numberticket;
    }

    public function setNumberticket(string $numberticket): self
    {
        $this->numberticket = $numberticket;

        return $this;
    }

    public function getDatechoice(): ?\DateTimeInterface
    {
        return $this->datechoice;
    }

    public function setDatechoice(\DateTimeInterface $datechoice): self
    {
        $this->datechoice = $datechoice;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIspaid(): ?bool
    {
        return $this->ispaid;
    }

    public function setIspaid(bool $ispaid): self
    {
        $this->ispaid = $ispaid;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getAddressReservation(): ?string
    {
        return $this->AddressReservation;
    }

    public function setAddressReservation(string $AddressReservation): self
    {
        $this->AddressReservation = $AddressReservation;

        return $this;
    }

    public function getStripesession(): ?string
    {
        return $this->stripesession;
    }

    public function setStripesession(?string $stripesession): self
    {
        $this->stripesession = $stripesession;

        return $this;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(?string $qrcode): self
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    public function getRatings(): ?Ratings
    {
        return $this->ratings;
    }

    public function setRatings(?Ratings $ratings): self
    {
        // unset the owning side of the relation if necessary
        if ($ratings === null && $this->ratings !== null) {
            $this->ratings->setReservation(null);
        }

        // set the owning side of the relation if necessary
        if ($ratings !== null && $ratings->getReservation() !== $this) {
            $ratings->setReservation($this);
        }

        $this->ratings = $ratings;

        return $this;
    }
}
