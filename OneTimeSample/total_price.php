<?php
class Item
{
    protected string $name;
    protected int $price;

    public function __construct($name, $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function purchaseLog($count, $total, $unit = "個"): void
    {
        echo sprintf("%s　%d%s　%s円\n", $this->name, $count, $unit, number_format($total));
    }

    public function calculate(int $count): int
    {
        $total = $this->price * $count;
        $this->purchaseLog($count, $total);
        return $total;
    }
}

class ItemByWeight extends Item
{
    const WEIGHT_UNIT = 100;
    const ROUND_DIGITS = -2;

    public function __construct($name, $price)
    {
        parent::__construct($name, $price);
    }

    public function calculate(int $weight): int
    {
        if ($weight < self::WEIGHT_UNIT) {
            throw new Exception(sprintf("%dグラム単位で指定してください。", self::WEIGHT_UNIT));
        }

        $count = round($weight, self::ROUND_DIGITS) / self::WEIGHT_UNIT;
        return parent::calculate($count);
    }
}

class ItemByLot extends Item
{
    private int $lotSize;

    public function __construct($name, $price, $lotSize)
    {
        $this->lotSize = $lotSize;
        parent::__construct($name, $price);
    }

    public function calculate(int $value): int
    {
        $count = ($value % $this->lotSize);
        $total = $this->price * $count;
        
        $lotCount = round($value / $this->lotSize);
        $lotTotal = ($this->price * ($value - $count));
        $lotTotal -= ($lotTotal / 100) * 5;

        $this->purchaseLog($lotCount, $lotTotal, "ケース");
        $this->purchaseLog($count, $total);

        return $lotTotal + $total;
    }
}

const TAX = 8;
$greenBellPepper = new Item("ピーマン", 50);
$beef = new ItemByWeight("牛肉", 380);
$soda = new ItemByLot("ソーダ", 120, 24);

$total = 0;
$total += $greenBellPepper->calculate(4);
$total += $beef->calculate(200);
$total += $soda->calculate(52);
echo sprintf("合計金額　%s円\n", number_format($total));

?>