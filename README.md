<p align="center" dir="auto">

<img src="https://camo.githubusercontent.com/cf25d81ab5acf028eda0aa2d361aca96198ef9d789a12a7e9b9931c8c799e297/68747470733a2f2f61646f6f7265692e73332e75732d656173742d322e616d617a6f6e6177732e636f6d2f696d616765732f6c6f6a655f74657374655f6c6f676f61646f6f7265695f313636323437363636332e706e67" width="160" data-canonical-src="https://adoorei.s3.us-east-2.amazonaws.com/images/loje_teste_logoadoorei_1662476663.png" style="max-width: 100%;">
</p>

# Desafio Adoorei - API Laravel

Este projeto consiste em uma API REST desenvolvida em Laravel para o Adoorei Checkout, uma aplicação fictícia da Loja ABC LTDA, destinada a registrar vendas de celulares. Desde a configuração inicial do ambiente com Docker até a implementação de funcionalidades avançadas, como testes unitários, documentação abrangente dos endpoints e a aplicação de práticas de desenvolvimento, tais como Clean Architecture e Conventional Commits.

**Principais Funcionalidades:**
- Listagem dos produtos disponíveis no banco de dados;
- Registro de novas vendas;
- Consulta das vendas realizadas;
- Busca de informações de uma venda específica;
- Cancelamento de vendas;
- Adição de novos produtos a uma venda.

**Tecnologias Empregadas:**
- Laravel;
- Docker;
- Swagger Documentation.

**Instruções para Execução:**
Certifique-se de que o Docker e o Docker Compose estejam instalados na máquina. Isso é necessário para criar e orquestrar os contêineres necessários para o projeto.

1. Git: Clone o projeto através do link: https://github.com/mrlonmra/desafio-backend-adoorei.git
2. Arquivo .env: Cópie o arquivo .envcopy e renomeie para .env e faça as alterações necessárias.
3. Docker Compose: Verifique se o arquivo docker-compose.yml está configurado corretamente, especialmente em relação aos serviços (app e mysql), portas expostas e redes.
4. Construa os Contêineres Docker: "docker-compose build"
5. Inicie os Contêineres Docker: "docker-compose up -d"
6. Instale as Dependências do Composer: "docker-compose exec app composer install"
7. Acesse a Aplicação: Após essas etapas, a aplicação Laravel deve estar acessível em http://localhost:8000.
8. A documentação completa dos endpoints está disponível no Swagger através do link http://localhost:8000/api/documentation#/default.

![image](https://github.com/mrlonmra/desafio-backend-adoorei/assets/26064875/c0520ffa-f0db-4350-8715-62fa34725b27)

 
**Contribuições:**
- Contribuições são incentivadas! Sinta-se à vontade para abrir problemas ou enviar solicitações de pull.

**Agradecimentos:**
- Este projeto foi desenvolvido como parte de um desafio técnico para o processo seletivo da Adoorei. Agradeço pela oportunidade e permaneço à disposição para esclarecimentos sobre o código.
