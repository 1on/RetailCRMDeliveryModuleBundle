<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Entity;

use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class Account
{
    /**
     * @Serializer\Groups({"get"})
     * @Serializer\Type("string")
     */
    protected $id;

    protected $clientId;

    /**
     * @var \DateTime
     *
     * @Serializer\Groups({"get"})
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @Serializer\Groups({"get", "connect"})
     * @Serializer\Type("string")
     */
    protected $crmUrl;

    /**
     * @var string
     *
     * @Serializer\Groups({"get", "connect"})
     * @Serializer\Type("string")
     */
    protected $crmApiKey;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var bool
     */
    protected $freeze;

    /**
     * @var string
     */
    protected $language;

    public function __construct()
    {
        $this->clientId = Uuid::uuid4();
        $this->createdAt = new \DateTime();
        $this->active = false;
        $this->freeze = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setClientId(UuidInterface $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientId(): ?UuidInterface
    {
        return $this->clientId;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCrmUrl(): ?string
    {
        return $this->crmUrl;
    }

    public function setCrmUrl(?string $crmUrl): self
    {
        $this->crmUrl = rtrim($crmUrl, '/');

        return $this;
    }

    public function getCrmApiKey(): ?string
    {
        return $this->crmApiKey;
    }

    public function setCrmApiKey(?string $crmApiKey): self
    {
        $this->crmApiKey = $crmApiKey;

        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setFreeze(bool $freeze): self
    {
        $this->freeze = $freeze;

        return $this;
    }

    public function isFreeze(): bool
    {
        return $this->freeze;
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\Type("boolean")
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("isEnabled")
     */
    public function isEnabled(): bool
    {
        return !$this->freeze && $this->active;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }
}
