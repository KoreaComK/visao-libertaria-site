# Repositório do Site Visão Libertária

É necessário que você tenha o composer instalado no seu servidor local

Execute `composer install` na pasta raiz do site http://localhost/visao-libertaria-site

# Criando a base de dados do Visão Libertária

Configurar o arquivo env, trocando o nome para .env

Executar o criador de banco de dados e tabelas `http://localhost/visao-libertaria-site/Inicializandobancodados`

Configurar o arquivo Email dentro de app/Config/Email.php

Executar o comando no CLI para popular as tabelas
`> php spark db:seed Main`