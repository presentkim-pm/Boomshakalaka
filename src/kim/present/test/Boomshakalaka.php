<?php
declare(strict_types=1);

namespace kim\present\test;

use pocketmine\block\BlockLegacyIds;
use pocketmine\entity\Location;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\item\SpawnEgg;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

final class Boomshakalaka extends PluginBase implements Listener{
    /** @var \Closure[] (int) item id => \Closure(Player $player, Item $item) : void */
    private array $useHandlers = [];

    protected function onLoad() : void{
        $this->useHandlers[ItemIds::TNT] = function(Player $player, Item $_) : void{
            $i = 0;
            foreach($player->getLineOfSight(120) as $block){
                while($block->getSide(Facing::DOWN)->getId() === BlockLegacyIds::AIR && $block->getPos()->y >= 0){
                    $block = $block->getSide(Facing::DOWN);
                }
                $entity = new PrimedTNT(Location::fromObject($block->getPos(), $player->getWorld()), CompoundTag::create()->setShort("Fuse", (int) (10 + (++$i / 2))));
                $entity->spawnToAll();
            }
        };
        $this->useHandlers[ItemIds::SPAWN_EGG] = function(Player $player, Item $item) : void{
            if(!$item instanceof SpawnEgg)
                return;

            foreach($player->getLineOfSight(40) as $block){
                while($block->getSide(Facing::DOWN)->getId() === BlockLegacyIds::AIR && $block->getPos()->y >= 0){
                    $block = $block->getSide(Facing::DOWN);
                }

                $item->onInteractBlock($player, $block, $block, Facing::UP, new Vector3(0, 0, 0));
            }
        };
        $this->useHandlers[ItemIds::DIAMOND_SWORD] = function(Player $player, Item $_) : void{
            foreach($player->getWorld()->getEntities() as $entity){
                if(!$entity instanceof $player){
                    $entity->flagForDespawn();
                }
            }
        };
    }

    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerItemUse(PlayerItemUseEvent $event) : void{
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $executor = $this->useHandlers[$item->getId()] ?? null;
        if($executor !== null){
            $event->cancel();
            ($executor)($player, $item);
        }
    }
}