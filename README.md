# pharmanexo-v2
Phamanexo version 2

# Instruções de execução
Após clonar o repositório em sua máquina local, execute os seguintes passos para configurar o ambiente e rodar o projeto.
Nota: é necessário ter o Docker e o Docker-Compose instalados.

1. Crie a rede interna com o comando: "docker network create pharmanexo-network";
2. Instancie os containers com o comando: "docker-compose up -d --build";

    2.1. Caso seja necessário reconstruir o banco de dados (por alguma alteração em sua estrutura, por exemplo), executar o seguinte comando: "docker container rm pharmanexo-db && docker-compose up --force-recreate pharmanexo_db". O output exibe toda a rotina de importação dos arquivos em docker-compose/mysql/ e, caso existam, os erros do processo;
    
    2.2. Volte ao passo 2 e instancie os containers normalmente;

3. Instale as dependências do projeto com o comando: "composer install" (este passo pode ser executado na máquina hospedeira, host, ou diretamente no container);
4. Abra o seu navegador e visite a seguinte URL: "http://localhost:8080".