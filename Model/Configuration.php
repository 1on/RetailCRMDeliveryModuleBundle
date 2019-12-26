<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

use RetailCrm\DeliveryModuleBundle\Model\Status;
use RetailCrm\DeliveryModuleBundle\Model\Plate;
use RetailCrm\DeliveryModuleBundle\Model\DeliveryDataField;

class Configuration
{
    const PAYER_TYPE_RECEIVER = 'receiver';
    const PAYER_TYPE_SENDER = 'sender';

    /**
     * Описание подключения
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     */
    public $description;

    /**
     * Относительные пути от базового URL до конкретных методов
     * @var array
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("actions")
     * @Serializer\Type("array<string, string>")
     */
    public $actions;

    /**
     * Допустивые типы плательщиков за доставку
     * @var array
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("payerType")
     * @Serializer\Type("array")
     */
    public $payerType;

    /**
     * Максимальное количество заказов при печати документов
     * @var integer
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("platePrintLimit")
     * @Serializer\Type("integer")
     */
    public $platePrintLimit = 100;

    /**
     * В методе calculate расчитывается стоимость доставки
     * @var boolean
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("rateDeliveryCost")
     * @Serializer\Type("boolean")
     */
    public $rateDeliveryCost = true;

    /**
     * Разрешить использование упаковок
     * @var boolean
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("allowPackages")
     * @Serializer\Type("boolean")
     */
    public $allowPackages = false;

    /**
     * Доставка наложенным платежом доступна/не доступна
     * @var boolean
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("codAvailable")
     * @Serializer\Type("boolean")
     */
    public $codAvailable = false;

    /**
     * Возможен самопривоз на терминал.
     * @var boolean
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("selfShipmentAvailable")
     * @Serializer\Type("boolean")
     */
    public $selfShipmentAvailable = false;

    /**
     * Разрешить отдельно передавать трек номер
     * @var string
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("allowTrackNumber")
     * @Serializer\Type("boolean")
     */
    public $allowTrackNumber;

    /**
     * Список стран откуда можно отправить посылку. Если массив пустой, то нет ограничения на страны
     * @var array
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("availableCountries")
     * @Serializer\Type("array")
     */
    public $availableCountries;

    /**
     * Список обязательных полей заказа
     * @var array
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("requiredFields")
     * @Serializer\Type("array")
     */
    public $requiredFields;

    /**
     * Список статусов службы доставки
     * @var Status[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("statusList")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Status>")
     */
    public $statusList;

    /**
     * Список печатных форм, предоставляемых службой
     * @var Plate[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("plateList")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Plate>")
     */
    public $plateList;

    /**
     * Список дополнительных полей, необходимых для оформления доставки
     * @var DeliveryDataField[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("deliveryDataFieldList")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\DeliveryDataField>")
     */
    public $deliveryDataFieldList;

    /**
     * Список дополнительных полей, необходимых для заявки на отгрузку
     * @var DeliveryDataField[]
     *
     * @Serializer\Groups({"set", "get"})
     * @Serializer\SerializedName("shipmentDataFieldList")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\DeliveryDataField>")
     */
    public $shipmentDataFieldList;
}
