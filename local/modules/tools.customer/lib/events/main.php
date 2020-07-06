<?php

declare(strict_types=1);

namespace Tools\Customer\Events;

use Bitrix\Main\Localization\Loc,
    Tools\Customer\InfoCustomerTable,
    Tools\Customer\Pre;

Loc::loadMessages(__FILE__);

class Main
{
    /**
     * Событие для формы просмотра заказа.
     *
     * @return array
     */
    function OnAdminSaleOrderView(): array
    {
        return [
            "TABSET" => "CostomerInfoIPTabs",
            "GetTabs" => ["Tools\Customer\Events\Main", "getTabs"],
            "ShowTab" => ["Tools\Customer\Events\Main", "showTabs"]
        ];
    }

    /**
     *  Возвращает массив вкладок
     *
     * @param array $args
     * @return array
     */
    function getTabs(array $args): array
    {
        return [
            [
                "DIV" => "CostomerInfoIPTabs",
                "TAB" => Loc::getMessage('TAB'),
                "TITLE" => Loc::getMessage('TITLE'),
                "SORT" => 100
            ]
        ];
    }

    /**
     *  Выводит вкладку
     *
     * @param string $tabName
     * @param array $args
     * @param $varsFromForm
     * @return void
     */
    function showTabs(string $tabName, array $args, $varsFromForm): void
    {
        $iterator = InfoCustomerTable::GetList(
            [
                'select' => ['data'],
                'filter' => ['order_id' => $args['ID']],
                'limit' => 1
            ]
        );
        if ($arItem = $iterator->fetch()) {
            $data = unserialize($arItem['data']);
            Pre::pretty_print($data);
        }
        else{
            echo Loc::getMessage('EMPTY');
        }
    }
}