<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Customer
{
    /**
     * Идентификатор покупателя
     * @var integer
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("id")
     * @Serializer\Type("integer")
     */
    public $id;

    /**
     * Фамилия
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("lastName")
     * @Serializer\Type("string")
     */
    public $lastName;

    /**
     * Имя
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("firstName")
     * @Serializer\Type("string")
     */
    public $firstName;

    /**
     * Отчество
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("patronymic")
     * @Serializer\Type("string")
     */
    public $patronymic;

    /**
     * Телефоны
     * @var array
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("phones")
     * @Serializer\Type("array<string>")
     */
    public $phones;

    /**
     * E-mail
     * @var string
     *
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("email")
     * @Serializer\Type("string")
     */
    public $email;
}
