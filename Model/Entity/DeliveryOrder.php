<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Entity;

abstract class DeliveryOrder
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var int
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var bool
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
