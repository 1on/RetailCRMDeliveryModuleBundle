<?php

namespace RetailCrm\DeliveryModuleBundle\Service;

class PinbaService
{
    /**
     * @param array    $tags
     * @param \Closure $handler
     *
     * @return mixed
     */
    public function timerHandler(array $tags, \Closure $handler)
    {
        if (function_exists('pinba_timer_start')) {
            $timer = pinba_timer_start($tags);
            $response = $handler();
            pinba_timer_stop($timer);
        } else {
            $response = $handler();
        }

        return $response;
    }
}
