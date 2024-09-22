<?php

echo <<< EOF
◆◆使い方◆◆◆◆◆

ブラウザ上中央にある{Run（Ctrl-Enter）}ボタンを押すか
キーボードで{Ctrl + Enter}を同時押しすると実行できます。

◆◆◆◆◆◆◆◆◆◆
EOF;

$note = <<< EOF
◆◆注意◆◆◆◆◆◆

遅延処理や動作の遅い処理はタイムアウトなどで失敗する場合があります。

また、サーバー起因でエラーが発生した場合、コード自体に問題はなくても失敗する場合があります。
その場合は、時間をおいて再度実行していただければ成功する可能性があります。

台本に関しては、生成AIにて作成後に編集しています。
内容の間違いや言葉遣いに違和感がありましたらすみません。

◆◆◆◆◆◆◆◆◆◆
EOF;

class Content{
    
    // キャラクター
    const HUMAN = 0;
    const CAT = 1;
    const DOG = 2;

    public int $avatar;
    public string $name;
    public string $message;

    public function __construct($avatar, $name, $message){
        $this->avatar = $avatar;
        $this->name = $name;
        $this->message = $message;
    }
    
    public function getAvatar(){
        return match($this->avatar){
            self::HUMAN => '( b A b)',
            self::CAT => '^._.^',
            self::DOG => '(^. ^ U)',
            default => ''
        };
    }
    
    public function getPostLabel(){
        return sprintf("%s::%s", $this->getAvatar(), $this->name);
    }
}

$contents = (object)[
    new Content(Content::HUMAN, '太郎', "最近プログラミングを始めたんだけど、環境構築ってのが難しくてさ。なんか良い方法ないかな？"),
    new Content(Content::DOG, 'ポチ' ,"クンクン、太郎くん、それはPaiza.ioを使ってみたらどうかな？\nブラウザ上でコードを書いてすぐに実行できる、便利なオンライン実行環境なんだ。\n環境構築の手間が一切なくて、プログラミングに集中できるよ。"),
    new Content(Content::CAT, 'ニャンコ' ,"そうニャン！Paiza.ioは、C、C++、Java、Python、PHP、JavaScriptなど、たくさんのプログラミング言語に対応しているから、いろんな言語を試せるのも魅力的なんだ。"),
    new Content(Content::HUMAN, '太郎' ,"へぇー、すごい！じゃあ、PHPとかJavaScriptのコードも書いて実行できるってこと？"),
    new Content(Content::DOG, 'ポチ' ,"もちろん！Paiza.ioのOnline editor and compilerを使えば、好きな言語を選んで、すぐにコードを書いて実行できるよ。\n例えば、Pythonで「Hello, World!」と表示するコードを書いて、実行ボタンを押すだけで、すぐに結果を確認できるんだ。"),
    new Content(Content::CAT, 'ニャンコ' ,"そうニャン！JavaScriptで簡単な計算をするプログラムを書いてみることもできるよ。\nPaiza.ioは、コードを書いて実行するまでのサイクルが短いから、プログラミングの学習効率がすごく上がるんだ。"),
    new Content(Content::DOG, 'ポチ' ,"Paiza.ioは、プログラミングの学習だけでなく、ちょっとしたコードのテストや、アイデアの検証にも使えるから、ぜひ使ってみてほしいな。"),
    new Content(Content::HUMAN, '太郎' ,"わぁ、Paiza.ioってすごいんだね！早速使ってみようかな。"),
    new Content(Content::CAT, 'ニャンコ' ,"Paiza.ioは、プログラミング初心者から経験者まで、幅広く利用できるツールだから、きっと気に入ると思うよ。"),
    new Content(Content::DOG, 'ポチ' ,"URLを共有すると同じプログラムを共有できるから、コードベースの相談をしたいときにも便利だよ。"),
];

echo "\n\n\n";
foreach ($contents as $index => $value) {
    $postAt = (new DateTime())->modify(sprintf("+%s minute + %s second", (60 * ($index * 0.5)) + ($index * 3), rand(0, 60)))->format('Y-m-d h:i:s');
    echo sprintf("%s >> %s\n%s\n\n", $value->getPostLabel(), $postAt, $value->message);
}
echo "\n\n\n";

echo $note;
?>