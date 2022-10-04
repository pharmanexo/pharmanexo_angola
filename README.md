# pharmanexo-v2
Phamanexo version 2

# Instruções de execução
Após clonar o repositório em sua máquina local, execute os seguintes passos para configurar o ambiente e rodar o projeto.
Nota: é necessário ter o Docker e o Docker-Compose instalados.

1. Crie a rede interna com o comando: "docker network create pharmanexo-network";
2. Suba os containers com o comando: "docker-compose up -d --build";
3. Instale as dependências do projeto com o comando: "composer install";
4. Abra o seu navegador e visite a seguinte URL: "http://localhost:8080".