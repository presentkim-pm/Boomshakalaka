<?php

declare(strict_types=1);

namespace kim\present\test\tests;

use kim\present\test\Boomshakalaka;
use PHPUnit\Framework\TestCase;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

final class UseHandlerTest extends TestCase{
    private function handlerOf(Item $item) : ?\Closure{
        $class = new \ReflectionClass(Boomshakalaka::class);
        $plugin = $class->newInstanceWithoutConstructor();
        return $class->getMethod("getUseHandler")->invoke($plugin, $item);
    }

    public function testTntHasHandler() : void{
        self::assertNotNull($this->handlerOf(VanillaBlocks::TNT()->asItem()));
    }

    public function testSpawnEggHasHandler() : void{
        self::assertNotNull($this->handlerOf(VanillaItems::ZOMBIE_SPAWN_EGG()));
    }

    public function testDiamondSwordHasHandler() : void{
        self::assertNotNull($this->handlerOf(VanillaItems::DIAMOND_SWORD()));
    }

    public function testOtherItemsHaveNoHandler() : void{
        self::assertNull($this->handlerOf(VanillaItems::IRON_SWORD()));
        self::assertNull($this->handlerOf(VanillaBlocks::DIRT()->asItem()));
        self::assertNull($this->handlerOf(VanillaItems::AIR()));
    }
}
