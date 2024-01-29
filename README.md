# Repositório do Site Visão Libertária

É necessário que você tenha o composer instalado no seu servidor local

Execute `composer install` na pasta raiz do site http://localhost/visao-libertaria-site

# Criando a base de dados do Visão Libertária

Configurar o arquivo env, trocando o nome para .env

Executar o criador de banco de dados e tabelas `http://localhost/visao-libertaria-site/Inicializandobancodados`

Configurar o arquivo Email dentro de `app/Config/Email.php`

Executar o comando no CLI para popular as tabelas
`> php spark db:seed Main`

# Utilizando Docker

Caso queira utilizar o Docker para rodar o projeto, siga os passos abaixo (não é necessário realizar os passos acima descritos):

- Instale e inicie o Docker em sua máquina.
- Execute o comando `make up`. Este comando irá criar a imagem e iniciar os containers.
- Acesse a url `http://localhost:8080/Inicializandobancodados` para criar as tabelas no banco de dados.
- Acesse o container da aplicação com o comando `make bash-web`. Dentro do container execute o comando `php spark db:seed Main` para popular as tabelas.
- Configure o arquivo Email dentro de `app/Config/Email.php`.

Agora é só acessar a url `http://localhost:8080`.