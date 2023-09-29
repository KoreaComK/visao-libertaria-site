# Repositório do Site Visão Libertária

# Criando a base de dados do Visão Libertária

Comentar a linha `database.default.database` no arquivo .env

Executar o criador de banco de dados e tabelas http://localhost/vl20/public/Inicializandobancodados

Descomentar a linha `database.default.database` no arquivo .env

Executar o comando no CLI para popular as tabelas
`> php spark db:seed Main`
