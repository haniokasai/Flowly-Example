namespace FlowyExample;

use Flowy\Flowy\Flowy;
use function Flowy\listen;

use pocketmine\event\player\PlayerMoveEvent;

class HeySiri extends Flowy{
	function onEnable(){
		$this->start($this->test());
	}

　　	function test(){
		$event = yield listen(PlayerJoinEvent::class);
		$player = $event->getPlayer();
		$player->sendMessage(“moving”);
	}
}