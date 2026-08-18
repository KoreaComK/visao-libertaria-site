# CanonicalHostFilter

## Objetivo

Garante que o pedido HTTP use o mesmo hostname de `app.baseURL` quando o visitante chegou pelo par `www`/apex do mesmo domínio.

## Dependências

- `Config\App` (`baseURL`, `allowedHostnames`)
- `redirect()` do CodeIgniter

## Lógica central

Compara o host do pedido com o host de `baseURL`. Se forem o mesmo domínio registrável, diferindo só pelo prefixo `www`, responde 301 para o mesmo path/query no host (e scheme) do `baseURL`. Hosts listados em `allowedHostnames` e hosts de outro domínio não são alterados.

## Assinaturas

- `before(RequestInterface $request, $arguments = null): ResponseInterface|void` — redireciona ou segue o fluxo.
- `after(RequestInterface $request, ResponseInterface $response, $arguments = null): void` — sem efeito.
