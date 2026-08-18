# CORS no login e recursos bloqueados em www

## Sintoma

No console, com a página aberta em `https://www.visaolibertaria.com`:

- POST AJAX para `https://visaolibertaria.com/site/login` bloqueado por CORS
- `images/favicon.png` em 404
- `main.js` com `ERR_BLOCKED_BY_RESPONSE.NotSameOriginAfterDefaultedToSameOriginByCoep`

## Causa raiz

1. **CORS:** `baseURL` de produção é o apex (`visaolibertaria.com`). O layout público gera o login com `base_url() . 'site/login'`. `www` e apex são origens diferentes; o endpoint não envia `Access-Control-Allow-Origin`.
2. **Sem redirect de host:** o `.htaccess` só redireciona `www` em HTTP. Produção é HTTPS em nginx/Plesk, então `https://www.visaolibertaria.com` permanece no `www`.
3. **Favicon:** sobra do tema em `layouts/_main.php` apontando para `images/favicon.png`, arquivo inexistente.
4. **COEP / `main.js`:** o repositório não tem `main.js` nem header `Cross-Origin-Embedder-Policy`. O HTML de produção também não envia COEP. O `main.js?attr=...` é script injetado no cliente (padrão de antivírus/extensão, ex.: Kaspersky), não um asset do site.

## Correção

- Filtro `canonicalHost` redireciona o par `www`/apex para o host de `app.baseURL`.
- Removido o `<link>` de `images/favicon.png`.
