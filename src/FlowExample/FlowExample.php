<?php
namespace FlowExample;

use function Flowy\delay;
use Flowy\Flowy;
use function Flowy\listen;

use pocketmine\event\player\PlayerMoveEvent;//使うイベントを記載するように。
use pocketmine\event\player\PlayerInteractEvent;


class FlowExample extends Flowy{//extendsで各種関数を取り入れます。

//+------------------------------------------------------------------+
//|起動時の読み込み。                                                |
//+------------------------------------------------------------------+
    function onEnable(){
        $this->start($this->test());//test関数を１度だけ呼び出します。
        $this->start($this->test2());//test2関数を１度だけ呼び出します。
        $this->start($this->test3());//test3関数を１度だけ呼び出します。
	}
//+------------------------------------------------------------------+
//|動くと、moving と言われる機能　　　　                             |
//+------------------------------------------------------------------+
    function test(){
        while(true){//永久にループを抜けることはありません。
            /** @var PlayerMoveEvent $event */
            $event = yield listen(PlayerMoveEvent::class); //イベントを待機させる
            $player = $event->getPlayer();
            $player->sendMessage("moving".time());
        }
    }
//+------------------------------------------------------------------+
//|石ブロックを触ると、interactedと言われる。（１度だけ）            |
//+------------------------------------------------------------------+
    function test2(){
        /** @var PlayerInteractEvent $event */
        $event = yield listen(PlayerInteractEvent::class)//イベントを待機させる
            ->filter(
                /**Interactイベントが起きて　なおかつ　この関数がtrueを返すことを条件と設定します。
                 条件が満たされれば、”実行部分”の実行に移ります。そうでなければ、イベントが起こるのを待ち続けます。  **/
                function ($ev) {
                    /** @var PlayerInteractEvent $ev */
                    return
                    $ev->getBlock()->getId() == 1;//stone
            });

        //実行部分
        $event->getPlayer()->sendMessage("interacted".time());

        //一度上のメッセージ送信が終われば、このtest2のイベント待機のための関数は終了される、二度目以降の石をタップする行為を検出することはありません。
    }
//+------------------------------------------------------------------+
//|草ブロックを触ると、3秒を数えてunixtimeを言われる。（何回でも）   |
//+------------------------------------------------------------------+
    function test3(){
         /** @var PlayerInteractEvent $event */
        $event = yield listen(PlayerInteractEvent::class)
            ->filter(
                function ($ev) {
                    /** @var PlayerInteractEvent $ev */
                    return
                        $ev->getBlock()->getId() == 2;//grass

                });

        $event->getPlayer()->sendMessage("please wait".time());
        yield delay(20*3); //遅延を作ってみます。 20tick = 1秒 を３倍しています。
        $event->getPlayer()->sendMessage("after 3 second...".time());
        $this->start($this->test3());//test3関数を１度だけ呼び出します。自身をループして呼び出すことになるので、test１のようにwhileで挟んだような振る舞いをします
    }
}