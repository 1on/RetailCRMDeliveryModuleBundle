<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Manager
{
    /**
     * Идентификатор менеджера.
     *
     * @var int
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("id")
     * @Serializer\Type("integer")
     */
    public $id;

    /**
     * Фамилия.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("lastName")
     * @Serializer\Type("string")
     */
    public $lastName;

    /**
     * Имя.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("firstName")
     * @Serializer\Type("string")
     */
    public $firstName;

    /**
     * Отчество.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("patronymic")
     * @Serializer\Type("string")
     */
    public $patronymic;

    /**
     * Телефон.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("phone")
     * @Serializer\Type("string")
     */
    public $phone;

    /**
     * E-mail.
     *
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("email")
     * @Serializer\Type("string")
     */
    public $email;

    public function getNickName(): ?string
    {
        $result = trim(
            $this->lastName . ' ' . $this->firstName . ' ' . $this->patronymic
        );

        return $result;
    }
}
