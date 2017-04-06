<?php
namespace Lenses;

use DateTime;

class Lenses
{
    private $customers;
    private $goods;

    public function __construct()
    {
        $this->customers = self::populateCustomers();
        $this->goods = self::populateGoods();
    }

    public function run()
    {
        for ($i = 0; $i < sizeof($this->customers); $i++) {
            $orders = $this->customers[$i]['orders'];

            $analysis = (new Analysis($orders, $this->goods))->analyze();

            echo '<h2>Customer ' . $i . ' </h2>';
            echo '<pre>';
            var_dump($orders);
            foreach ($analysis as $id => $v1) {
                foreach ($v1 as $power => $e2) {
                    echo '<p>Customer ' . $i . ' will need lenses of type ' . $id . ' with power ' . $power . ' on '
                        . $e2['predictedExpirationDate'] . '</p>';
                }
            }
            echo '</pre>';
        }
    }

    private static function populateCustomers(): array
    {
        $cstms = [];
        $cstms[] = [
            'orders' => [
                '2015-04-01' => [
                    [1, 2, '-2.00'],
                    [1, 2, '-3.00'],
                ],
            ]
        ];
        $cstms[] = [
            'orders' => [
                '2014-10-01' => [
                    [3, 2, '-1.50'],
                    [3, 2, '-3.50'],
                ],
                '2015-01-01' => [
                    [3, 2, '-1.50'],
                    [3, 2, '-3.50'],
                ],
                '2015-04-15' => [
                    [3, 2, '-1.50'],
                    [3, 2, '-3.50'],
                ],
            ]
        ];
        $cstms[] = [
            'orders' => [
                '2014-08-01' => [
                    [2, 2, '+0.50'],
                ],
            ]
        ];
        return $cstms;
    }

    private static function populateGoods(): array
    {
        $goods = [
            1 => 180, // Biofinity (6 lenses)
            2 => 90, // Biofinity (3 lenses)
            3 => 30, // Focus Dailies (30)
        ];
        return $goods;
    }


}
