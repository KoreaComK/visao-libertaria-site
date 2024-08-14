# Repositório do Site Visão Libertária

É necessário que você tenha o composer instalado no seu servidor local

Execute `composer install` na pasta raiz do site http://localhost/visao-libertaria-site

# Criando a base de dados do Visão Libertária

- Configure o arquivo env, trocando o nome para .env
- Executar o comando no CLI para criar as tabelas
`> php spark migrate`
- Execute o comando no CLI para popular as tabelas
`> php spark db:seed Main`
- Configure o arquivo Email dentro de `app/Config/Email.php`

# Utilizando Docker

Caso queira utilizar o Docker para rodar o projeto, siga os passos abaixo (não é necessário realizar os passos descritos acima):

- Instale e inicie o Docker em sua máquina.
- Execute o comando `make up`. Este comando irá criar a imagem e iniciar os containers.
- Execute o comando `make migrate` para criar as tabelas no banco de dados.
- Execute o comando `make seed` para popular as tabelas no banco de dados.
- Configure o arquivo Email dentro de `app/Config/Email.php`.

Agora acesse a url `http://localhost:8080`.