# demo-hollis-hearth

**Hollis & Hearth** is a fictional homewares shop in Asheville, North Carolina,
selling stoneware, linens and candles online through a real WordPress and
WooCommerce store. Two things on it are broken, the way things break on real
stores after an update, and [Sarcio](https://www.sarcio.io) fixes both live:
one in the browser, one on the server.

|           |                                                                        |
| --------- | ---------------------------------------------------------------------- |
| Live demo | [demos.sarcio.io/hollis-hearth](https://demos.sarcio.io/hollis-hearth) |
| Shows     | a front-end fix and a server-side fix through the Sarcio WordPress plugin, with no sidecar |
| Fix PR    | opens on this repository (GitHub)                                      |

WordPress core, WooCommerce and the official **SQLite drop-in** (no database
server) come from wordpress.org, and the Sarcio plugin is an ordinary Composer
dependency, exactly as a customer installs it. The plugin fetches and verifies
the site's signed fixes itself with the site's sidecar key.

## What's broken

1. **"There's no checkout button in the cart."** Theme 4.0 hides the cart's and
   the cart drawer's checkout buttons to make room for a sticky mobile cart bar
   (`wp-content/themes/hollis-hearth/style.css`) that never shipped, so a full
   cart has nowhere to go. A fix approved in Sarcio brings the button back for
   every visitor; the pull request with the permanent fix edits the stylesheet.
2. **"Checkout says to choose how many reward points to redeem. There's
   nowhere to choose that."** The rewards plugin
   (`wp-content/mu-plugins/hollis-rewards.php`) made `redeem_points` a required
   checkout field in 2.2, but the box that sends it only exists in a checkout
   template that isn't live yet, so every order is refused. A fix approved in
   Sarcio supplies the missing value (no points) while the permanent fix is
   reviewed. If Sarcio is unavailable the store behaves exactly as it did
   before.

## Run it

```bash
docker build --secret id=composer_auth,src=auth.json -t demo-hollis-hearth .
docker run -p 4015:4015 -e SARCIO_BASE_PATH=/hollis-hearth demo-hollis-hearth
# http://localhost:4015/hollis-hearth
```

Environment: `PORT`, `SARCIO_BASE_PATH`, `SARCIO_SITE_KEY`, `SARCIO_API_URL`,
`SARCIO_WIDGET_SRC`, `SARCIO_SIDECAR_KEY` (the site's `sk_` key, for the server
tier), and `HOLLIS_ADMIN_PASSWORD` for wp-admin. Behind a proxy, send
`X-Forwarded-Proto` so WordPress emits URLs in the visitor's scheme.
The site key, the secret key and the admin password are applied each time the
container starts (`connect.php`), so Settings → Sarcio shows the keys the way a
site owner would have pasted them in.

The store is stocked at build time (`setup.php`, then `provision.php`) and the
database is baked into the image, so recreating the container resets the store
to its broken state. It takes no payments: its one payment method is a
no-charge demo method.

Photos are from Unsplash, used under the Unsplash License (see
[`assets/CREDITS.md`](assets/CREDITS.md)).
