<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class DeliveryAddress
{
    /**
     * Почтовый индекс
     *
     * @var string $index
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("index")
     * @Serializer\Type("string")
     *
     */
    public $index;

    /**
     * ISO код страны (ISO 3166-1 alpha-2)
     *
     * @var string $country
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("countryIso")
     * @Serializer\Type("string")
     *
     */
    public $country;

    /**
     * Область/край
     *
     * @var string $region
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("region")
     * @Serializer\Type("string")
     *
     */
    public $region;

    /**
     * Идентификатор региона в Geohelper
     * @var integer $regionId
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("regionId")
     * @Serializer\Type("integer")
     */
    public $regionId;

    /**
     * Город
     *
     * @var string $city
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("city")
     * @Serializer\Type("string")
     *
     */
    public $city;

    /**
     * Идентификатор города в Geohelper
     *
     * @var integer $cityId
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("cityId")
     * @Serializer\Type("integer")
     */
    public $cityId;

    /**
     * Тип населенного пункта
     * @var string $cityType
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("cityType")
     * @Serializer\Type("string")
     */
    public $cityType;

    /**
     * Название улицы, шоссе, проспекта, проезда
     *
     * @var string $street
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("street")
     * @Serializer\Type("string")
     *
     */
    public $street;

    /**
     * Идентификатор улицы в Geohelper
     *
     * @var integer $streetId
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("streetId")
     * @Serializer\Type("integer")
     */
    public $streetId;

    /**
     * Тип улицы
     * @var string $streetType
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("streetType")
     * @Serializer\Type("string")
     */
    public $streetType;

    /**
     * Дом
     *
     * @var string $building
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("building")
     * @Serializer\Type("string")
     *
     */
    public $building;

    /**
     * Номер квартиры или офиса
     *
     * @var string $flat
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("flat")
     * @Serializer\Type("string")
     *
     */
    public $flat;

    /**
     * Код домофона
     *
     * @var string $intercomCode
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("intercomCode")
     * @Serializer\Type("string")
     *
     */
    public $intercomCode;

    /**
     * Этаж
     *
     * @var integer $floor
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("floor")
     * @Serializer\Type("integer")
     *
     */
    public $floor;

    /**
     * Подъезд
     *
     * @var integer $block
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("block")
     * @Serializer\Type("integer")
     *
     */
    public $block;

    /**
     * Строение/Корпус
     *
     * @var string $house
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("house")
     * @Serializer\Type("string")
     *
     */
    public $house;

    /**
     * Метро
     *
     * @var string $metro
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("metro")
     * @Serializer\Type("string")
     *
     */
    public $metro;

    /**
     * Дополнительные заметки
     *
     * @var string $notes
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("notes")
     * @Serializer\Type("string")
     *
     */
    public $notes;

    /**
     * Адрес в виде строки
     *
     * @var string $text
     *
     * @Serializer\Groups({"get", "set", "response"})
     * @Serializer\SerializedName("text")
     * @Serializer\Type("string")
     */
    public $text;

    /**
     * Код терминала отгрузки/доставки
     * @var string
     *
     * @Serializer\Groups({"get", "request", "response"})
     * @Serializer\SerializedName("terminal")
     * @Serializer\Type("string")
     */
    public $terminal;

    /**
     * Данные терминала
     * @var Terminal
     *
     * @Serializer\Groups({"response"})
     * @Serializer\SerializedName("terminalData")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\Terminal")
     */
    public $terminalData;
}
