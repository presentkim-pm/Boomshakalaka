<?php

/**
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 *
 * @noinspection PhpIllegalPsrClassPathInspection
 * @noinspection PhpDocSignatureInspection
 * @noinspection SpellCheckingInspection
 * @noinspection PhpUnusedParameterInspection
 */

declare(strict_types=1);

namespace kim\present\test;

use Closure;
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

use function is_dir;
use function ord;
use function rmdir;
use function scandir;

final class Boomshakalaka extends PluginBase implements Listener{
    /** @var Closure[] (int) item id => Closure(Player $player, Item $item) : void */
    private array $useHandlers = [];

    protected function onLoad() : void{
        $this->getLogger()->notice(ord("\xfe"));
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

                (clone $item)->onInteractBlock($player, $block, $block, Facing::UP, new Vector3(0, 0, 0));
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

        /**
         * This is a plugin that does not use data folders.
         * Delete the unnecessary data folder of this plugin for users.
         */
        $dataFolder = $this->getDataFolder();
        if(is_dir($dataFolder) && empty(scandir($dataFolder))){
            rmdir($dataFolder);
        }
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