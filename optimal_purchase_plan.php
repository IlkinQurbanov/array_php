<?php

function findOptimalPurchasePlan(array $priceList, int $N): array
{
    // Сортируем прайс-лист по цене в порядке возрастания
    usort($priceList, fn($a, $b) => $a['price'] <=> $b['price']);

    // Инициализируем массив для хранения минимальной стоимости для каждого количества товара
    $dp = array_fill(0, $N + 1, INF);
    $dp[0] = 0; // Стоимость для 0 товаров равна 0

    // Инициализируем массив для хранения плана закупки
    $plan = array_fill(0, $N + 1, []);

    // Проходим по каждому предложению в прайс-листе
    foreach ($priceList as $offer) {
        $id = $offer['id'];
        $count = $offer['count'];
        $price = $offer['price'];
        $pack = $offer['pack'];

        // Проходим по каждому возможному количеству товара, которое нужно закупить
        for ($i = $pack; $i <= $N; $i += $pack) {
            $prev = $i - $pack;
            $cost = $dp[$prev] + $price;

            if ($cost < $dp[$i]) {
                $dp[$i] = $cost;
                $plan[$i] = $plan[$prev];
                $plan[$i][] = ['id' => $id, 'qty' => $pack];
            }
        }
    }

    // Возвращаем план закупки для количества товара N
    return $plan[$N];
}

// Пример использования
$priceList = [
    ['id' => 111, 'count' => 42, 'price' => 13, 'pack' => 1],
    ['id' => 222, 'count' => 77, 'price' => 11, 'pack' => 10],
    ['id' => 333, 'count' => 103, 'price' => 10, 'pack' => 50],
    ['id' => 444, 'count' => 65, 'price' => 12, 'pack' => 5],
];

$N = 76;

$result = findOptimalPurchasePlan($priceList, $N);
print_r($result);