# Dockerized

Projeto para criação de ambiente com docker (varios containers)

<h4>Comando:</h4>
./envinit.sh [install|delete|refresh] [repo|skip] [configuration: true|false]

<h4>Parâmetros Aceito:</h4>
Parâmetro 1: [install|delete|refresh]
<pre>
install: Instala o ambiente
delete: Remove o ambiente
refresh: Atualiza o ambiente
</pre>
Parâmetro 2: [repo|skip]
<pre>
repo: Informa que os repositórios configurados no arquivo configuration devem ser clonados/baixados do git
skip: Informa que os repositórios não devem ser clonados/baixados
</pre>
Parâmetro 3: [configuration: true|false]
<pre>
true: Use true caso queira utilizar as configurações do arquivo configuration
false: Para não usar o arquivo configuration (ignorar)
</pre>

<h4>Instalação</H4>
- 1: execute ./configure.sh para gerar o arquivo .configure
- 2: editeo arquivo .configure com as informações corretas
- 3: execute novamente o script ./configure.sh para configurar o ambiente
- 4: configure as informações no arquivo configuration.conf
- 5: execute ./envinit.sh install repo true (para rodar pela primeira vez)
- 6: execute ./envinit.sh delete repo true (para remover tudo)
- 7: execute ./envinit.sh refresh skip true

# Opções

O script ./configure apenas gera um arquivo de configuração modelo e também o arquivo de dados do usuario 
o qual é usado para fazer login no github.

O script ./envinit.sh recebe 3 argumentos como parametro:
    
- 1: Instalar ou Deletar
- 2: Incluir ou não repositorios do github no processo
- 3: Reconfigurar ou não o arquivo docker-compose.yml

# Exemplos

* Para instalar pela primeira vez: ./envinit.sh install repo true
* Para atualizar quaisquer alterações no projeto, use: ./envinit.sh refresh skip true
* Para remover tudo, inclusive containers e imagens, use: ./envinit.sh delete repo true

