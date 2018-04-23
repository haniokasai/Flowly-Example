<?php
namespace FlowExample;

use function Flowy\delay;
use Flowy\Flowy;
use function Flowy\listen;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerInteractEvent;


class FlowExample extends Flowy{
    function onEnable(){
        $this->start($this->test());
        $this->start($this->test2());
        $this->start($this->test3());
	}

    function test(){
        while(true){
            /** @var PlayerMoveEvent $event */
            $event = yield listen(PlayerMoveEvent::class);
            $player = $event->getPlayer();
            $player->sendMessage("moving".time());
        }
    }

    function test2(){
        /** @var PlayerInteractEvent $event */
        $event = yield listen(PlayerInteractEvent::class)
            ->filter(
                function ($ev) {
                    /** @var PlayerInteractEvent $ev */
                    return
                    $ev->getBlock()->getId() == 1;//stone
            });
        $event->getPlayer()->sendMessage("interacted".time());
    }

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
        delay(20*3000);
        $event->getPlayer()->sendMessage("after 3 second...".time());
        $this->start($this->test3());
    }
}