<?php

namespace RetailCrm\DeliveryModuleBundle\Model\Request;

use RetailCrm\DeliveryModuleBundle\Model\Customer;
use JMS\Serializer\Annotation as Serializer;
use RetailCrm\DeliveryModuleBundle\Model\Manager;

class RequestSave
{
    /**
     * Идентификатор доставки в службе доставки. Передается если требуется отредактировать уже оформленную доставку
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("deliveryId")
     * @Serializer\Type("string")
     */
    public $deliveryId;

    /**
     * Id заказа
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("order")
     * @Serializer\Type("string")
     */
    public $order;

    /**
     * Номер заказа
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("orderNumber")
     * @Serializer\Type("string")
     */
    public $orderNumber;

    /**
     * Код магазина
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("site")
     * @Serializer\Type("string")
     */
    public $site;

    /**
     * Название магазина
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("siteName")
     * @Serializer\Type("string")
     */
    public $siteName;

    /**
     * Наименование юр.лица
     * @var string
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("legalEntity")
     * @Serializer\Type("string")
     */
    public $legalEntity;

    /**
     * Покупатель
     * @var Customer
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("customer")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\Customer")
     */
    public $customer;

    /**
     * Менеджер, работающий с покупателем
     * @var Manager
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("manager")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\Manager")
     */
    public $manager;

    /**
     * Набор упаковок
     * @var RetailCrm\DeliveryModuleBundle\Model\Package[]
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("packages")
     * @Serializer\Type("array<RetailCrm\DeliveryModuleBundle\Model\Package>")
     */
    public $packages;

    /**
     * Данные доставки
     * @var RetailCrm\DeliveryModuleBundle\Model\SaveDeliveryData
     *
     * @Serializer\Groups({"request"})
     * @Serializer\SerializedName("delivery")
     * @Serializer\Type("RetailCrm\DeliveryModuleBundle\Model\SaveDeliveryData")
     */
    public $delivery;

    /**
     * Валюта
     * @var string $currency
     *
     * @Serializer\Groups({"request", "calculate"})
     * @Serializer\SerializedName("currency")
     * @Serializer\Type("string")
     */
    public $currency;

    public function getFullDeclaredValue()
    {
        $result = 0;
        foreach ($this->packages as $package) {
            foreach ($package->items as $item) {
                $result += $item->declaredValue * $item->quantity;
            }
        }

        return $result;
    }

    public function getFullItemsCodValue()
    {
        $result = 0;
        foreach ($this->packages as $package) {
            foreach ($package->items as $item) {
                $result += $item->cod * $item->quantity;
            }
        }

        return $result;
    }
}
