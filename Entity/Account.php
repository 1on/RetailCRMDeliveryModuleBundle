<?php

namespace RetailCrm\DeliveryModuleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="account",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="account_crm_url_api_key_idx", columns={"crm_url", "crm_api_key"})
 *     }
 * )
 */
class Account
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @Serializer\Groups({"get"})
     * @Serializer\Type("string")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="now()"})
     * @Serializer\Groups({"get"})
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="crm_url", type="string", length=255, nullable=false)
     * @Serializer\Groups({"get", "connect"})
     * @Serializer\Type("string")
     */
    protected $crmUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="crm_api_key", type="string", length=255, nullable=false)
     * @Serializer\Groups({"get", "connect"})
     * @Serializer\Type("string")
     */
    protected $crmApiKey;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", options={"default"=true})
     */
    protected $active;

    /**
     * @var bool
     *
     * @ORM\Column(name="freezed", type="boolean", options={"default"=false})
     */
    protected $freeze;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=255, nullable=true)
     */
    protected $language;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new \DateTime();
        $this->active = false;
        $this->freeze = false;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
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
