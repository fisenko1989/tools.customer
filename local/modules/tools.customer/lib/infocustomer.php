<?php

declare(strict_types=1);

namespace Tools\Customer;

use Bitrix\Main\Entity\DataManager,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Web\HttpClient;

Loc::loadMessages(__FILE__);

/**
 * Class InfoCustomerTable
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> data string mandatory
 * </ul>
 *
 * @package Tools\Customer
 **/
class InfoCustomerTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'c_info_customer';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap(): array
    {
        return [
            'id' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('INFO_CUSTOMER_ENTITY_ID_FIELD'),
            ],
            'data' => [
                'data_type' => 'text',
                'required' => true,
                'title' => Loc::getMessage('INFO_CUSTOMER_ENTITY_DATA_FIELD'),
            ],
            'order_id' => [
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('INFO_CUSTOMER_ENTITY_ORDER_ID_FIELD'),
            ]
        ];
    }

    /**
     * Получить информацию по ip-адресу.
     *
     * @param string $ip передаем ip адрес
     * @return array получаем информацию по ip адресу
     */
    public static function getInfoIP(string $ip): array
    {
        $url = 'https://rest.db.ripe.net/search.json?query-string=' . $ip;
        $httpClient = new HttpClient();
        $response = $httpClient->get($url);
        if ($httpClient->getStatus() == 200) {
            $arData = \json_decode($response, true);
            if (is_array($arData)) {
                return $arData;
            }
        }
        return [];
    }
}