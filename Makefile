.PHONY: up down bash-web bash-db build help restart

up:
	docker-compose up -d

down:
	docker-compose down -v

bash-web:
	docker-compose exec -it web bash
	
bash-db:
	docker-compose exec -it db bash

build:
	docker-compose build web

restart: down up

test:
	docker-compose exec web bash -c "./vendor/bin/phpunit"

help:
	@echo "Available commands:"
	@echo ""
	@echo "up		Inicia os containers"
	@echo "down		Para os containers"
	@echo "bash-web	Acessa o bash do container web"
	@echo "bash-db		Acessa o bash do container db"
	@echo "build		Cria a imagem do container web"
	@echo "restart		Reinicia os containers"
	@echo "help		Exibe esta ajuda"