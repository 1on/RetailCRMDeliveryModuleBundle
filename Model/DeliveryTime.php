<?php

namespace RetailCrm\DeliveryModuleBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class DeliveryTime
{
    /**
     * Время доставки "с"
     *
     * @Serializer\SerializedName("from")
     * @Serializer\Type("DateTime<'H:i'>")
     * @Serializer\Groups({"set", "get", "orderHistory", "history-reference", "calculate"})
     * @Serializer\Accessor(getter="getFrom", setter="setFrom")
     *
     * @var \DateTime|null
     */
    protected $from;

    /**
     * Время доставки "до"
     *
     * @Serializer\SerializedName("to")
     * @Serializer\Type("DateTime<'H:i'>")
     * @Serializer\Groups({"set", "get", "orderHistory", "history-reference", "calculate"})
     * @Serializer\Accessor(getter="getTo", setter="setTo")
     *
     * @var \DateTime|null
     */
    protected $to;

    /**
     * Время доставки (произвольный текст)
     *
     * @Serializer\SerializedName("custom")
     * @Serializer\Type("string")
     * @Serializer\Groups({"set", "get", "orderHistory", "history-reference", "calculate"})
     *
     * @var string|null
     */
    protected $custom;

    /**
     * @param null|string|\DateTime $from
     * @param null|string|\DateTime $to
     * @param null|string           $custom
     *
     * @return self
     */
    public function __construct($from = null, $to = null, $custom = null)
    {
        $this->setFrom($from);
        $this->setTo($to);
        $this->setCustom($custom);
    }

    /**
     * Разбор строки со временем доставки
     *
     * @param string $time
     *
     * @return self
     */
    public static function fromString($time)
    {
        $result = new self();
        $result->setString($time);

        return $result;
    }

    /**
     * @return \DateTime|null
     */
    public function getFrom()
    {
        if ($this->from) {
            $this->from->setDate(1970, 01, 01);

            if ('00:00:00' === $this->from->format('H:i:s')) {
                return null;
            }
        }

        return $this->from;
    }

    /**
     * @param \DateTime|string|null $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $this->ensureTime($from);
        $this->ensureConsistency();

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTo()
    {
        if ($this->to) {
            $this->to->setDate(1970, 01, 01);

            if ('23:59:59' === $this->to->format('H:i:s')) {
                return null;
            }
        }

        return $this->to;
    }

    /**
     * @param \DateTime|string|null $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $this->ensureTime($to);
        $this->ensureConsistency();

        return $this;
    }

    /**
     * @return string
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param string $custom
     *
     * @return $this
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * @param string $time
     *
     * @return $this
     */
    public function setString($time)
    {
        // точное время: 12.30, 12:30
        $exactPattern = '/^в?\s*(\d{2}[:\.]\d{2})$/u';
        // диапазон времени: 12-13, c 12.00 по 13:00
        $rangePattern = '/^с?\s*(?P<from>\d{2}[:\.]?\d{0,2})\s*(-|по|до)\s*(?P<to>\d{2}[:\.]?\d{0,2})/u';
        // диапазон времени: c 12.00
        $rangeFromPattern = '/^с?\s*(?P<from>\d{2}[:\.]?\d{0,2})/u';
        // диапазон времени: до 13:00
        $rangeToPattern = '/^(-|по|до)\s*(?P<to>\d{2}[:\.]?\d{0,2})/u';

        if (preg_match($exactPattern, $time, $matches)) {
            $timeObj = new \DateTime($matches[1]);
            $this->setFrom(clone $timeObj);
            $this->setTo(clone $timeObj);
        } elseif (preg_match($rangePattern, $time, $matches)) {
            $from = $matches['from'];
            $to = $matches['to'];

            $from = preg_match($exactPattern, $from) ? $from : $from . ':00';
            $to = preg_match($exactPattern, $to) ? $to : $to . ':00';

            try {
                $this->setFrom(new \DateTime($from));
                $this->setTo(new \DateTime($to));
            } catch (\Exception $e) {
                $this->setFrom(null);
                $this->setTo(null);
                $this->setCustom($time);
            }
        } elseif (preg_match($rangeFromPattern, $time, $matches)) {
            $from = $matches['from'];
            $from = preg_match($exactPattern, $from) ? $from : $from . ':00';

            try {
                $this->setFrom(new \DateTime($from));
                $this->setTo(null);
            } catch (\Exception $e) {
                $this->setFrom(null);
                $this->setTo(null);
                $this->setCustom($time);
            }
        } elseif (preg_match($rangeToPattern, $time, $matches)) {
            $to = $matches['to'];
            $to = preg_match($exactPattern, $to) ? $to : $to . ':00';

            try {
                $this->setFrom(null);
                $this->setTo(new \DateTime($to));
            } catch (\Exception $e) {
                $this->setFrom(null);
                $this->setTo(null);
                $this->setCustom($time);
            }
        } else {
            $this->setFrom(null);
            $this->setTo(null);
            $this->setCustom($time);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getString()
    {
        $from   = $this->getFrom();
        $to     = $this->getTo();
        $custom = $this->getCustom();

        if (!($from || $to)) {
            return (string) $custom;
        }

        $fromPrint = $from ? $from->format('H:i') : null;
        $toPrint   = $to ? $to->format('H:i') : null;

        if ($fromPrint && $fromPrint === $toPrint) {
            return 'в ' . $fromPrint;
        }

        $str = '';
        if ($fromPrint) {
            $str .= 'с ' . $fromPrint;
        }
        if ($toPrint) {
            $str .= ' до ' . $toPrint;
        }

        return trim($str);
    }

    /**
     * Проверяет, соответствует ли время доставки диапазону из настроек
     *
     * @param  array $range
     * @return bool
     */
    public function equalsRange(array $range)
    {
        $fromEquals = false;
        $toEquals = false;

        $from = $this->getFrom();
        $to   = $this->getTo();

        if ($from) {
            if (isset($range['from'])) {
                $fromEquals = $from->format('H:i') === $range['from'];
            }
        } else {
            if (!isset($range['from']) ||
                !$range['from'] ||
                $range['from'] === '00:00' ||
                $range['from'] === '00:00:00'
            ) {
                $fromEquals = true;
            }
        }

        if ($to) {
            if (isset($range['to'])) {
                $toEquals = $to->format('H:i') === $range['to'];
            }
        } else {
            if (!isset($range['to']) ||
                !$range['to'] ||
                $range['from'] === '23:59' ||
                $range['from'] === '23:59:59'
            ) {
                $toEquals = true;
            }
        }

        return $fromEquals && $toEquals;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !($this->from || $this->to || $this->custom);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getString();
    }

    protected function ensureTime($time)
    {
        if ($time) {
            if (!$time instanceof \DateTime) {
                $time = new \DateTime((string) $time);
            }
            $time->setDate(1970, 01, 01);
        }

        return $time;
    }

    /**
     * Если для времени доставки указана только одна граница диапазона, то присвоим другой значение по умолчанию
     */
    protected function ensureConsistency()
    {
        $from = $this->getFrom();
        $to   = $this->getTo();

        if ($from === null && $to !== null) {
            $this->from = new \DateTime('1970-01-01T00:00:00');
        } elseif ($to === null && $from !== null) {
            $this->to = new \DateTime('1970-01-01T23:59:59');
        } elseif ($to === null && $from === null) {
            $this->to   = null;
            $this->from = null;
        }
    }
}
