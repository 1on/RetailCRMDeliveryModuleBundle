<?php

namespace RetailCrm\DeliveryModuleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class DeliveryOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $account;

    /**
     * @var int
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     */
    protected $orderId;

    /**
     * @var string
     *
     * @ORM\Column(name="external_id", type="string", length=255, nullable=false)
     */
    protected $externalId;

    /**
     * @var bool
     *
     * @ORM\Column(name="ended", type="boolean")
     */
    protected $ended;

    public function __construct()
    {
        $this->ended = false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setEnded(bool $ended): self
    {
        $this->ended = $ended;

        return $this;
    }

    public function getTrackNumber(): string
    {
        return $this->externalId;
    }

    public function setTrackNumber(string $trackNumber): self
    {
        return $this;
    }

    public function getEnded(): bool
    {
        return $this->ended;
    }
}
