# Dockerized

Projeto para criação de ambiente com docker (varios containers)

- 1: execute ./configure.sh
- 2: configure as informações no arquivo configuration.conf
- 3: execute ./envinit.sh install repo true (para rodar pela primeira vez)
- 4: execute ./envinit.sh delete repo true (para remover tudo)

# Opções

O script ./configure apenas gera um arquivo de configuração modelo e também o arquivo de dados do usuario 
o qual é usado para fazer login no github.

O script ./envinit.sh recebe 3 argumentos como parametro:
    
- 1: Instalar ou Deletar
- 2: Incluir ou não repositorios do github no processo
- 3: Reconfigurar ou não o arquivo docker-compose.yml

# Exemplos

* Para instalar pela primeira vez: ./envinit.sh install repo true

