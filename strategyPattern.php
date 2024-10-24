<?php
declare(strict_types=1);

 /**
 * SAMPLE ARCHITECTURE: PRICE SORTER ALGORITHM
 * STRATEGY PATTERN
 */

class Catalog {
    private array $products;

    public function __construct(array $products) {
        $this->products = $products;
    }

    public function getProducts(ProductSorter $productSorter) {
        return $productSorter->sort($this->products);
    }
}

interface ProductSorter {
    public function sort(array $products);
}

class PriceSorter implements ProductSorter {
    public function sort(array $products) {
        usort($products, [$this, 'sortByPrice']);
        return $products;
    }

    private function sortByPrice(array $productA, array $productB) {
        return $productA["price"] - $productB["price"];
    }
}

class SalesPerViewSorter implements ProductSorter {
    public function sort(array $products) {
        usort($products, [$this, 'sortBySalesPerView']);
        return $products;
    }

    private function sortBySalesPerView(array $productA, array $productB) {
        $ratioA = $productA["sales_count"] / $productA["views_count"];
        $ratioB = $productB["sales_count"] / $productB["views_count"];

        return $ratioA - $ratioB;
    }
}

/************************ SAMPLE DATA ********************************/

$products = [
    [
        'id' => 1,
        'name' => 'Alabaster Table',
        'price' => 12.99,
        'created' => '2019-01-04',
        'sales_count' => 32,
        'views_count' => 730,
    ],
    [
        'id' => 2,
        'name' => 'Zebra Table',
        'price' => 44.49,
        'created' => '2012-01-04',
        'sales_count' => 301,
        'views_count' => 3279,
    ],
    [
        'id' => 3,
        'name' => 'Coffee Table',
        'price' => 10.00,
        'created' => '2014-05-28',
        'sales_count' => 1048,
        'views_count' => 20123,
    ]
];

/************************ USAGE ********************************/

$productPriceSorter = new PriceSorter();
$productSalesPerViewSorter = new SalesPerViewSorter();

$catalog = new Catalog($products);
$productsSortedByPrice = $catalog->getProducts($productPriceSorter);
$productsSortedBySalesPerView = $catalog->getProducts($productSalesPerViewSorter);

echo "Sort By Price:\n";
foreach ($productsSortedByPrice as $product) {
    echo 'ID: ' . $product['id'] . ', Name: ' . $product['name'] . ', Price: $' . $product['price'] . "\n";
}

echo "\nSort By Sales per View:\n";
foreach ($productsSortedBySalesPerView as $product) {
    $ratio = $product['sales_count'] / $product['views_count'];
    echo 'ID: ' . $product['id'] . ', Name: ' . $product['name'] . ', Sales/Views Ratio: ' . number_format($ratio, 4) . "\n";
}
