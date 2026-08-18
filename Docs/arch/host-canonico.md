# Host canônico

O host canônico do site é o hostname de `app.baseURL` (produção: `visaolibertaria.com`, sem `www`).

`www` e o domínio apex são origens distintas para o navegador. CSS, JS e AJAX gerados por `site_url()` / `base_url()` apontam para o host do `baseURL`. Quem acessa pelo `www` dispara CORS no login e carrega estáticos de outra origem.

O servidor de produção é nginx + Plesk. As regras de rewrite de `www` no `.htaccess` não se aplicam. A canonicalização é feita pelo filtro `canonicalHost` (`App\Filters\CanonicalHostFilter`), que responde 301 para o host do `baseURL` quando o pedido chega no par `www`/apex do mesmo domínio.

Hosts em `App::$allowedHostnames` não são redirecionados.
