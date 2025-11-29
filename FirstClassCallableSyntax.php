<?php

// First class callable syntax
class Test
{
    private function pow($value)
    {
        return (object) ['default' => $value, 'pow' => $value ** 2];
    }

    public function getValues()
    {
        return array_map(self::pow(...), range(1, 100));
    }
}

$test = new Test();
foreach ($test->getValues() as $value) {
    echo sprintf("default => %3d, pow => %5d\n", $value->default, $value->pow);
}

?>