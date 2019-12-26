<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Package
{
    /**
     * Идентификатор упаковки
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("packageId")
     * @Serializer\Type("string")
     */
    public $packageId;

    /**
     * Вес г.
     * @var float
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("weight")
     * @Serializer\Type("float")
     */
    public $weight;

    /**
     * Ширина мм.
     * @var integer
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("width")
     * @Serializer\Type("integer")
     */
    public $width;

    /**
     * Длина мм.
     * @var integer
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("length")
     * @Serializer\Type("integer")
     */
    public $length;

    /**
     * Высота мм.
     * @var integer
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("height")
     * @Serializer\Type("integer")
     */
    public $height;

    /**
     * Содержимое упаковки
     * @var PackageItem[]
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("items")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\PackageItem>")
     */
    public $items;

    public function __construct($weight = null, $width = null, $length = null, $height = null)
    {
        $this->weight = $weight;
        $this->width = $width;
        $this->length = $length;
        $this->height = $height;
    }

    public function getVolume()
    {
        if (!is_null($this->length)
            && !is_null($this->width)
            && !is_null($this->height)
        ) {
            return $this->length * $this->width * $this->height;
        } else {
            return false;
        }
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata
            ->addPropertyConstraint('weight', new Assert\NotBlank());
    }
}
