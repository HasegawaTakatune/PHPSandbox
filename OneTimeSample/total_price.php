<?php

/*
各商品の値段と個数から合計金額を求めるシステムを作成したいと考えています。

商品には単品・重量・ケースで購入することができる。

重量で購入する場合は、100g単位で計算する。
・100g未満の購入はエラーとする
・10の位を四捨五入する

ケースで購入する場合には、ケースの個数分とケース未満の個数分をそれぞれ計算する。
・ケースで購入した分は5%割り引かれる
*/

/**
 * 通常商品
 */
class Product
{
    /**
     * 商品名
     * @var string
     */
    protected string $name;

    /**
     * 価格
     * @var int
     */
    protected int $price;

    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * 購入ログ出力
     * @param mixed $count 個数
     * @param mixed $total 合計額
     * @param mixed $unit 単位
     * @return void
     */
    public function purchaseLog($count, $total, $unit = '個'): void
    {
        echo sprintf("%s\t\t% 8d%- 8s\t\t\t% 8s円\n", $this->name, $count, $unit, number_format($total));
    }

    /**
     * 商品の個数分の合計額を計算する
     * @param int $count 個数
     * @return int 合計額
     */
    public function calculate(int $count): int
    {
        // 金額を計算
        $total = $this->price * $count;
        $this->purchaseLog($count, $total);
        return $total;
    }
}

/**
 * 重量商品
 */
class ProductByWeight extends Product
{
    /**
     * 重量/個数の単位
     * @var int
     */
    const WEIGHT_UNIT = 100;

    /**
     * まるめ桁数
     * @var int
     */
    const ROUND_DIGITS = -2;

    public function __construct($name, $price)
    {
        parent::__construct($name, $price);
    }

    public function calculate(int $weight): int
    {
        if ($weight < self::WEIGHT_UNIT) {
            throw new Exception(sprintf('%dグラム単位で指定してください。 weight: %d', self::WEIGHT_UNIT, $weight));
        }

        // グラムから個数を割り出す
        $count = round($weight, self::ROUND_DIGITS) / self::WEIGHT_UNIT;

        // 金額を計算
        $total = $this->price * $count;
        parent::purchaseLog($weight, $total, 'グラム');
        return $total;
    }
}

/**
 * ロット商品
 */
class ProductByLot extends Product
{
    /**
     * ロット/個数の単位
     * @var int
     */
    private int $lotSize;

    public function __construct($name, $price, $lotSize)
    {
        $this->lotSize = $lotSize;
        parent::__construct($name, $price);
    }

    public function calculate(int $value): int
    {
        // 単品での金額を計算
        $count = $value % $this->lotSize;
        $total = $this->price * $count;

        // ロットでの金額を計算
        $lotTotal = $this->price * ($value - $count);
        $lotTotal -= ($lotTotal / 100) * 5;

        $this->purchaseLog(round($value / $this->lotSize), $lotTotal, 'ケース');
        $this->purchaseLog($count, $total);

        // ロット + 単品の合計金額を計算
        return $lotTotal + $total;
    }
}

/**
 * カートの中身を合算する
 * @param mixed $carts
 * @return void
 */
function calculateInCart($carts = [])
{
    echo "ーーーーーーーーーーーーーーーーーーーーーーー\n\n";

    $total = 0;
    foreach ($carts as $cart) {
        $total += $cart->product->calculate($cart->value);
    }

    echo sprintf("合計金額　%s円\n", number_format($total));
}

// 各種商品を定義
$greenBellPepper = new Product('ピーマン', 50);
$beef = new ProductByWeight('牛肉', 380);
$soda = new ProductByLot('ソーダ', 120, 24);

// 各種計算を実行
calculateInCart([
    (object) ['product' => $greenBellPepper, 'value' => 4],
    (object) ['product' => $beef, 'value' => 200],
    (object) ['product' => $soda, 'value' => 52]
]);

calculateInCart([
    (object) ['product' => $greenBellPepper, 'value' => 100],
    (object) ['product' => $beef, 'value' => 100],
    (object) ['product' => $soda, 'value' => 100]
]);

calculateInCart([
    (object) ['product' => $greenBellPepper, 'value' => 1],
    (object) ['product' => $beef, 'value' => 100],
    (object) ['product' => $soda, 'value' => 1]
]);

?>