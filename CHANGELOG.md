# Changelog

## 2.0.0 (2026-11-27)

The plugin for PocketMine-MP API 4 is kept on the
[`legacy/pmmp-5.44`](https://github.com/presentkim-pm/boomshakalaka/tree/legacy/pmmp-5.44) branch.

### ⚠️ Breaking changes
- Targets [axolotl-pm/PocketMine-MP](https://github.com/axolotl-pm/PocketMine-MP) `5.51` (BedrockProtocol 64,
  Bedrock `1.26.60`) and **PHP 8.4+**.
- Distributed as a phar from GitHub Releases.
- The source is flattened to `src/` (`plugin.yml` has `src-namespace-prefix`).

### 🐛 Fixes
- `onLoad()` logged a leftover debug value with `Logger::notice(int)`, which is a `TypeError` under `strict_types`.
  It is removed.
- The primed TNT and spawn egg lookups walked down only to `y = 0`; they now stop at the world's minimum height
  (`-64` in the overworld).
- Removing the empty data folder no longer fails when `scandir()` returns `false`.

### 🔄 Changes
- Items are matched by type (`ItemBlock` of `TNT`, any `SpawnEgg`, `ItemTypeIds::DIAMOND_SWORD`) instead of legacy
  numeric item IDs; every spawn egg now works, not only the one with ID `SPAWN_EGG`.
- `composer.json` added (Composer is only used for development and testing).

### 🧪 Tests
- PHPUnit tests and GitHub Actions CI on PHP 8.4 / 8.5.
