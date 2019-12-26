<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Contragent
{
    const TYPE_INDIVIDUAL = 'individual';       // физ. лицо
    const TYPE_LEGAL_ENTITY = 'legal-entity';   // юр. лицо
    const TYPE_ENTERPRENEUR = 'enterpreneur';   // инд. предприниматель

    /**
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("type")
     * @Serializer\Type("string")
     */
    public $type;

    /**
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("legalName")
     * @Serializer\Type("string")
     */
    public $legalName;

    /**
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("legalAddress")
     * @Serializer\Type("string")
     */
    public $legalAddress;

    /**
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("INN")
     * @Serializer\Type("string")
     */
    public $INN;

    /**
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("OKPO")
     * @Serializer\Type("string")
     */
    public $OKPO;

    /**
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("KPP")
     * @Serializer\Type("string")
     */
    public $KPP;

    /**
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("OGRN")
     * @Serializer\Type("string")
     */
    public $OGRN;

    /**
     * @Serializer\Groups({"get"})
     * @Serializer\SerializedName("OGRNIP")
     * @Serializer\Type("string")
     */
    public $OGRNIP;
}
