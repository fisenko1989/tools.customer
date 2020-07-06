<?php

declare(strict_types=1);

namespace Tools\Customer;

/**
 * Class Pre
 *
 * @package Tools\Customer
 **/
class Pre
{

    /**
     * Выводит массив в виде дерева
     *
     * @param array $in - Массив который надо обойти
     * @param string $opened - Раскрыть дерево элементов по-умолчанию или нет?
     *
     * @return void
     */
    public static function pretty_print(array $in, string $opened = 'open'): void
    {
        if (is_object($in) or is_array($in)) {
            echo '<div>';
            echo '<details' . $opened . '>';
            echo '<summary>';
            echo (is_object($in)) ? 'Object {' . count((array)$in) . '}' : 'Array [' . count($in) . ']';
            echo '</summary>';
            self::pretty_print_rec($in, $opened);
            echo '</details>';
            echo '</div>';
        }
    }

    /**
     * Выводит массив в виде дерева
     *
     * @param array $in - Массив который надо обойти
     * @param string $opened - Раскрыть дерево элементов по-умолчанию или нет?
     * @param int $margin
     * @return void
     */
    public static function pretty_print_rec(array $in, string $opened, int $margin = 10): void
    {
        if (!is_object($in) && !is_array($in)) {
            return;
        }

        foreach ($in as $key => $value) {
            if (is_object($value) or is_array($value)) {
                echo '<details style="margin-left:' . $margin . 'px" ' . $opened . '>';
                echo '<summary>';
                echo (is_object($value)) ? $key . ' {' . count((array)$value) . '}' : $key . ' [' . count($value) . ']';
                echo '</summary>';
                self::pretty_print_rec($value, $opened, $margin + 10);
                echo '</details>';
            } else {
                switch (gettype($value)) {
                    case 'string':
                        $bgc = 'red';
                        break;
                    case 'integer':
                        $bgc = 'green';
                        break;
                }
                echo '<div style="margin-left:' . $margin . 'px">' . $key . ' : <span style="color:' . $bgc . '">' . $value . '</span></div>';
            }
        }
    }
}