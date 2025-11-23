<?php
// 可変関数の呼び出しテスト
class FOO
{
    public function callVariable()
    {
        $methodName = 'myMethod';
        $methodName('FOO');
    }

    public function callVariable2()
    {
        $methodName = 'myMethod';
        self::$methodName('FOO');
    }

    public function myMethod($value)
    {
        echo sprintf("%sから呼び出しを行い、%sクラスのメソッドが呼ばれました。\n", $value, __CLASS__);
    }
}

class BAR extends FOO
{
}

class HOGE extends FOO
{
    public function callVariable()
    {
        $methodName = 'myMethod';
        $methodName('HOGE');
    }

    public function callVariable2()
    {
        $methodName = 'myMethod';
        self::$methodName('HOGE');
    }

    public function myMethod($value)
    {
        echo sprintf("%sから呼び出しを行い、%sクラスのメソッドが呼ばれました。\n", $value, __CLASS__);
    }
}

function myMethod($value)
{
    echo sprintf("%sから呼び出しを行い、ローカルのメソッドが呼ばれました。\n", $value);
}

function main()
{
    echo "\n";

    $method = 'myMethod';
    $method('ローカル');
    echo "\n";

    $foo = new FOO();
    $foo->$method('ローカル');
    $foo->callVariable();
    $foo->callVariable2();
    echo "\n";

    $bar = new BAR();
    $bar->$method('ローカル');
    $bar->callVariable();
    $bar->callVariable2();
    echo "\n";

    $hoge = new HOGE();
    $hoge->$method('ローカル');
    $hoge->callVariable();
    $hoge->callVariable2();
}
?>