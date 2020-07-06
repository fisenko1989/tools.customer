<?php

declare(strict_types=1);

namespace Tools\Customer\Events;

use Bitrix\Main\Service\GeoIp\Manager;
use Tools\Customer\InfoCustomerTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Order;

Loc::loadMessages(__FILE__);

class Sale
{
    /**
     * Событие вызывается после создания и расчета обьекта заказа.
     *
     * @param Order $order
     * @return void
     * @throws \Exception
     */
    public function OnSaleOrderSaved(Order $order): void
    {
        if (!$order->isNew()) {
            return;
        }

        if ($order->getId()) {
            $arData = InfoCustomerTable::getInfoIP(Manager::getRealIp());
            if($arData['objects']['object']){
                InfoCustomerTable::add(['order_id' => $order->getId(), 'data' => \serialize($arData['objects']['object'])]);
            }
        }
    }
}