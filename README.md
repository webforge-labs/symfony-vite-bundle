# symfony-vite-bundle

Integrate symfony with vite

## installation

add
```
\Vite\WebforgeViteBundle::class => ['all' => true],
```
to your bundles.php

### environment variables

```
INTERNAL_WEB_ADDR='http://nginx'
VITE_DEV_SERVER=http://myapp.local.dev:3030
APP_CDN=
```

`VITE_DEV_SERVER` point this to the full url where your vite dev server runs (if you run `vite`). Your browser needs to have access to this url.
`INTERNAL_WEB_ADDR` point this to your internal web server. Your PHP (container) needs to get access via this url to the files written by vite into the public folder.

`APP_CDN` special setup for production, if you use a cdn.

