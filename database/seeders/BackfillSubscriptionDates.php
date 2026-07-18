<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BackfillSubscriptionDates extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (\App\Models\Order::where('order_status', 'completed')->whereNull('start_date')->get() as $order) {
            $product = \App\Models\Product::where('brand', $order->brand)
                ->where('plan', $order->plan)
                ->first();

            $days = 30;
            if ($product && !empty($product->duration_days)) {
                $days = intval($product->duration_days);
            } else {
                $planLower = strtolower($order->plan);
                if (str_contains($planLower, 'day')) {
                    preg_match('/\d+/', $planLower, $matches);
                    $days = isset($matches[0]) ? intval($matches[0]) : 1;
                } elseif (str_contains($planLower, 'month')) {
                    preg_match('/\d+/', $planLower, $matches);
                    $months = isset($matches[0]) ? intval($matches[0]) : 1;
                    $days = $months * 30;
                } elseif (str_contains($planLower, 'year')) {
                    preg_match('/\d+/', $planLower, $matches);
                    $years = isset($matches[0]) ? intval($matches[0]) : 1;
                    $days = $years * 365;
                }
            }

            $order->start_date = $order->created_at;
            $order->end_date = $order->created_at->addDays($days);
            $order->save();
        }
    }
}
