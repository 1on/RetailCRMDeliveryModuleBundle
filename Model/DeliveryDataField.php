<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class DeliveryDataField
{
    const TYPE_INTEGER = 'integer';
    const TYPE_TEXT = 'text';
    const TYPE_AUTOCOMPLETE = 'autocomplete';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_CHOICE = 'choice';
    const TYPE_DATE = 'date';

    /**
     * Код поля
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("code")
     * @Serializer\Type("string")
     */
    public $code;

    /**
     * Имя поля
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("label")
     * @Serializer\Type("string")
     */
    public $label;

    /**
     * Пояснение к полю
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("hint")
     * @Serializer\Type("string")
     */
    public $hint;

    /**
     * Тип поля. Возможны варианты (
     * integer - числовое поле,
     * text - текстовое поле,
     * autocomplete - автокомплит поле,
     * checkbox,
     * choice - выпадающий список,
     * date - поле с датой)
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("type")
     * @Serializer\Type("string")
     */
    public $type;

    /**
     * Указывается для типа поля choice. Означает что можно выбирать несколько вариантов
     * @var boolean
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("multiple")
     * @Serializer\Type("boolean")
     */
    public $multiple = false;

    /**
     * Указывается для типа поля choice. Список возможных вариантов в выпадающем списке. Обязателен если тип поля choice
     * @var array
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("choices")
     * @Serializer\Type("array")
     */
    public $choices;

    /**
     * Указывается для типа поля autocomplete. Адрес, по окторому можно получить данные для автокомплит поля.
     * @var string
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("autocompleteUrl")
     * @Serializer\Type("string")
     */
    public $autocompleteUrl;

    /**
     * Поле обязательно для заполнения
     * @var boolean
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("required")
     * @Serializer\Type("boolean")
     */
    public $isRequired = false;

    /**
     * Поле влияет на стоимость доставки. Если true - значение поля используется в методе calculate
     * @var boolean
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("affectsCost")
     * @Serializer\Type("boolean")
     */
    public $isAffectsCost = false;

    /**
     * Разрешено ли редактировать поле.
     * Если false - поле информационное - заполняется только данными,
     * полученными напрямую от службы доставки (
     * например стоимость страховки - может заполняться после оформления доставки или при расчете стоимости)
     * @var boolean
     *
     * @Serializer\Groups({"get", "set"})
     * @Serializer\SerializedName("editable")
     * @Serializer\Type("boolean")
     */
    public $isEditable = true;
}
