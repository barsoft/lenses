<?php

namespace Lenses;

use DateTime;

class Analysis
{
    private $orders;
    private $items;
    private $goods;
    private $analysis;


    public function __construct($orders, $goods)
    {
        $this->orders = $orders;
        $this->goods = $goods;
        $this->items = [];
        $this->analysis = [];
    }

    public function analyze()
    {
        $this->initItems();
        $this->calcRealDaysLeft();
        $this->analysis();
        return $this->analysis;
    }

    /**
     * Helper to loop through 3 dimensional array of lenses since we have 3 keys (id, power, date). We will need
     * all three keys to analyze required data.
     */
    private function loopOrders($callback)
    {
        foreach ($this->items as $id => $e1) {
            foreach ($e1 as $power => $e2) {
                foreach ($e2 as $date => $e3) {
                    $callback($id, $power, $date);
                }
            }
        }
    }

    /**
     * Multiply quantity of ordered lenses pairs and the duration of lenses of certain type to determine estimated
     * exploitation period after a certain order was made.
     */
    private function initItems()
    {
        $dates = array_keys($this->orders);
        for ($i = 0; $i < sizeof($dates); $i++) {
            $date = $dates[$i];
            $order = $this->orders[$date];

            for ($j = 0; $j < sizeof($order); $j++) {
                list($id, $quantity, $power) = $order[$j];
                if (empty($this->items[$id][$power][$date]['estimatedDaysLeft'])) {
                    $this->items[$id][$power][$date]['estimatedDaysLeft'] = 0;
                }
                $this->items[$id][$power][$date]['estimatedDaysLeft'] += $quantity * $this->goods[$id];
            }
        }
    }

    /**
     * Calculate real time between orders of the same type of lenses
     */
    private function calcRealDaysLeft()
    {
        $this->loopOrders(function ($id, $power, $date) {
            $dates = array_keys($this->items[$id][$power]);
            if ($date == end($dates)) {
                return;
            }
            $nextDate = $dates[array_search($date, $dates) + 1];

            if (empty($this->items[$id][$power][$date]['realDaysLeft'])) {
                $this->items[$id][$power][$date]['realDaysLeft'] = 0;
            }
            $dStart = new DateTime($date);
            $dEnd = new DateTime($nextDate);
            $dDiff = $dStart->diff($dEnd);
            $this->items[$id][$power][$date]['realDaysLeft'] += $dDiff->days;
        });
    }

    /**
     * Analyze existing historical data.
     * Case 1 default (no history for the current type of lenses)
     *      predicted expiration date = order date + lenses duration
     * Case 2 (have historical data for certain type of lenses)
     *      predicted expiration date = order date + lenses duration * predictionCoef
     *      predictionCoef is calculated as realDaysLeftlSum / estimatedDaysLeftSum
     *      realDaysLeftlSum is a sum of real time of exploitation of lenses
     *      estimatedDaysLeftSum is a sum of estimated time of exploitation of lenses
     */
    private function analysis()
    {
        $this->loopOrders(function ($id, $power, $date) {
            // Set estimated expiration date of lenses as default
            $lastOrder = end($this->items[$id][$power]);
            $lastDate = key($this->items[$id][$power]);

            // If there are no historical data for this item go to next item
            if (sizeof($this->items[$id][$power]) <= 1) {
                $this->analysis[$id][$power]['predictedExpirationDate'] =
                    (new DateTime($lastDate . ' + ' . $lastOrder['estimatedDaysLeft'] . ' days'))
                        ->format('Y-m-d');
                return;
            }

            $this->sumHistoricalData($id, $power, $date);

            if ($lastDate == $date) {
                $this->predict($id, $power);
            }
        });
    }

    private function sumHistoricalData($id, $power, $date)
    {
        if (empty($this->analysis[$id][$power]['estimatedDaysLeftSum'])) {
            $this->analysis[$id][$power]['estimatedDaysLeftSum'] = 0;
        }

        $this->analysis[$id][$power]['estimatedDaysLeftSum'] += $this->items[$id][$power][$date]['estimatedDaysLeft'];

        if (empty($this->analysis[$id][$power]['realDaysLeftlSum'])) {
            $this->analysis[$id][$power]['realDaysLeftlSum'] = 0;
        }

        if (!empty($this->items[$id][$power][$date]['realDaysLeft'])) {
            $this->analysis[$id][$power]['realDaysLeftlSum'] += $this->items[$id][$power][$date]['realDaysLeft'];
        }
    }

    private function predict($id, $power)
    {
        $lastOrder = end($this->items[$id][$power]);
        $lastDate = key($this->items[$id][$power]);
        $this->analysis[$id][$power]['predictionCoef'] =
            $this->analysis[$id][$power]['realDaysLeftlSum'] / $this->analysis[$id][$power]['estimatedDaysLeftSum'];

        $this->analysis[$id][$power]['predictedExpirationDate'] =
            (new DateTime($lastDate . ' + '
                . round($lastOrder['estimatedDaysLeft'] * $this->analysis[$id][$power]['predictionCoef']) . ' days'))
                ->format('Y-m-d');
    }


}