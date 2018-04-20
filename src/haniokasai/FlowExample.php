<?php
namespace FlowyExample;

use Flowy\Flowy;
use function Flowy\listen;

use pocketmine\event\player\PlayerMoveEvent;

class FlowExample extends Flowy{
	function onEnable(){
		$this->start($this->test());
	}

	function test(){
        /** @var PlayerMoveEvent $event */
        $event = yield listen(PlayerMoveEvent::class);
		$player = $event->getPlayer();
		$player->sendMessage("moving");
	}
}