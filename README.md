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

## usage

Extend your base.html.twig with a template like this:

```twig
{% extends 'base.html.twig' %}

{% block title %}YAY Checkout{% endblock %}

{% block body %}
    <div id="app" class="hide-on-load">
        <shopping-frame/>
    </div>
{% endblock %}

{% block stylesheets %}
    {{ vite_entry_link_tags('app') }}
{% endblock %}

{% block javascripts %}
    {{ vite_entry_script_tags('app') }}
{% endblock %}
```

Given that you have a `app.js` in `assets`, you can reference it in your vite config like this (see rollup options: input): 

```js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueform from '@vueform/vueform/vite'
import tailwindcss from '@tailwindcss/vite'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
      vue(),
      vueform(),
      tailwindcss(),
  ],
  server: {
    host: '0.0.0.0',
    port: 3000,
    origin: process.env.VITE_DEV_SERVER,
    cors: {
      "origin": "*"
    },
    allowedHosts: [
        'myapp.local.dev'
    ],
    watch: {
      // needed if you want to reload dev server with twig
      disableGlobbing: false,
    },
    hmr: {
      clientPort: 3030
    }
  },
  root: ".",
  base: '/build/',
  publicDir: false,
  build: {
    manifest: true,
    emptyOutDir: true,
    assetsDir: "",
    outDir: "public/build/",
    rollupOptions: {
      input: {
        app: "./assets/app.js"
      },
    },
  },
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js'
    }
  }
})

```

Adjust "allowedHosts", manually with the same domain you reference in `VITE_DEV_SERVER`.

## Contribute

Install casey/just

```bash
just up
```

```bash
prep
```
(runs phpunit, phpstan, everything you need to make a commit).