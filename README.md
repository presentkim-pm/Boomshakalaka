<!-- PROJECT BADGES -->
<div align="center">

![Version][version-badge]
[![Stars][stars-badge]][stars-url]
[![License][license-badge]][license-url]

</div>


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <h3>Boomshakalaka</h3>
  <p align="center">
    A simple performance test tool for axolotl-pm/PocketMine-MP: blow up and spawn everything you are looking at.

[Changelog](CHANGELOG.md) · [Report a bug][issues-url] · [Request a feature][issues-url]

  </p>
</div>


<!-- ABOUT THE PROJECT -->

### Overview

- **What it does**
  - Right-click (use) one of the items below and it acts on every block along your line of sight.
  - The use event is cancelled, so the item itself is never placed or consumed by the normal action.
- **Items**
  - **TNT** : spawns a primed TNT on the ground along the line of sight (up to 120 blocks), with increasing fuse times.
  - **Spawn eggs** : spawns the egg's entity on the ground along the line of sight (up to 40 blocks).
  - **Diamond sword** : despawns every entity in your world except players.
- **Warning**
  - This is a stress-test tool. It has **no permission check**: give these items only to players you trust.

---

### Installation & Requirements

- **Server**
  - [axolotl-pm/PocketMine-MP](https://github.com/axolotl-pm/PocketMine-MP) `5.51` or higher (`plugin.yml` `api: [5.51.0]`),
    which uses BedrockProtocol 64 (Bedrock `1.26.60`).
  - PHP `8.4` or higher.
- **Installation**
  - Download `Boomshakalaka.phar` from [GitHub Releases][releases-url] and place it into your server's `plugins` directory.
  - Restart the server; the plugin will load automatically according to `plugin.yml`.
- **Old versions**
  - The version for PocketMine-MP API 4 is kept on the
    [`legacy/pmmp-5.44`](https://github.com/presentkim-pm/boomshakalaka/tree/legacy/pmmp-5.44) branch.
- **Development**
  - `composer install --ignore-platform-req='ext-*'` and `composer test`.

---

### License

Distributed under the **LGPL-3.0** License. See [LICENSE][license-url] for more information.

-----

[version-badge]: https://img.shields.io/github/v/release/presentkim-pm/boomshakalaka?display_name=tag&style=for-the-badge&label=VERSION

[stars-badge]: https://img.shields.io/github/stars/presentkim-pm/boomshakalaka.svg?style=for-the-badge

[license-badge]: https://img.shields.io/github/license/presentkim-pm/boomshakalaka.svg?style=for-the-badge

[stars-url]: https://github.com/presentkim-pm/boomshakalaka/stargazers

[releases-url]: https://github.com/presentkim-pm/boomshakalaka/releases

[issues-url]: https://github.com/presentkim-pm/boomshakalaka/issues

[license-url]: https://github.com/presentkim-pm/boomshakalaka/blob/main/LICENSE
