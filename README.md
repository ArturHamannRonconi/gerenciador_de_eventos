# Gerenciador De Eventos
---
Conteúdos
=================
   * [Sobre](#Sobre)
   * [Pré-requisitos](#Pré-requisitos)
   * [Instalação](#instalacao)
   * [Como usar](#como-usar)
   * [Tecnologias](#tecnologias)

## Sobre
__Ao começar o programa pensei em fazer algum design que fosse simples e que qualquer um pudesse utilizar.  
O objetivo do programa é cadastrar ou fazer uma consulta aos cadastros de cada participante, sala do evento  
ou local de intervalo. Quando estava fazendo pensei em algo como "perguntas e respostas",  
algo que qualquer um saberia usar, basicamente o programa te faz uma pergunta  
ou te pede algo e você só precisa responder conforme o que queira fazer.__  

## Pré-requisitos
__Para poder usar o software é necessário ter um servidor, um banco de dados e o php instalado na sua máquina.  
Não se preucupe, pois não é necessário baixar e instalar um por vez ou configurar um por um,  
existem pacotes como o [Xampp](https://www.apachefriends.org/pt_br/index.html) que faz isso por você.__  

## Instalação
__Para instalar o xampp basta clicar no link e baixar o instalador, você pode deixar as opções padrões que ele escolher.  
Assim que você terminar de instalar em sua máquina, procure a pasta na qual você instalou o xampp e entre na pasta dele e depois em htdocs  
apague todo o conteúdo dela,  
depois execute xampp como administrador.__  

![executando o xampp](https://github.com/ArturHamannRonconi/gerenciador_de_eventos/blob/master/assets/medias/executandoOXampp.png)    

__Após abrir ele você vai precisar iniciar 2 módulos,  
o Apache(servidor) e o MySQL(banco de dados), achar o nome deles no aplicativo do xampp e clicar em start em cada um.__  

![iniciando o Apache e o MySQL](https://github.com/ArturHamannRonconi/gerenciador_de_eventos/blob/master/assets/medias/iniciandoOApacheEOMySQL.png)  

__Depois de iniciar os dois você vai precisar entrar no banco de dados,  
para fazer isso apenas clique nesse [link](http://localhost/phpmyadmin),  
caso o link não funcione tente digitar localhost/phpmyadmin ou 127.0.0.1/phpmyadmin na URL do seu navegador.  
depois de entrar no phpmyadmin será necessário criar um banco de dados com esse exato nome `gerenciador_de_eventos`.__  

![criando o banco de dados](https://github.com/ArturHamannRonconi/gerenciador_de_eventos/blob/master/assets/medias/criandoBancoDeDados.png)  

![nomeando o banco de dados](https://github.com/ArturHamannRonconi/gerenciador_de_eventos/blob/master/assets/medias/nomeandoBancoDeDados.png)  

__Agora que você criou um banco de dados, precisamos dos arquivos aqui do github, baixe esse repositório dentro da pasta htdocs  
e caso a pasta que foi baixada com todos os arquivos tenha outro nome, nomeie para `gerenciador_de_eventos`.  
Agora é necessário que você importe as tabelas para o banco de dados,  
para fazer em primeiro lugar selecione a base de dados `gerenciador_de_eventos` que você criou.__  

![selecionando base de dados](https://github.com/ArturHamannRonconi/gerenciador_de_eventos/blob/master/assets/medias/selecionandoBaseDeDados.png)  

__Com a base de dados selecionada clique na aba importar.__  

![importando](https://github.com/ArturHamannRonconi/gerenciador_de_eventos/blob/master/assets/medias/importando.png)  

__Clique em escolher arquivo e vá até a pasta gerenciador_de_eventos que foi baixada do github,  
dentro da pasta procure outra pasta chamada `core` clique nela e selecione o arquivo ´gerencidor_de_eventos.sql´,  
após abri-lo apenas clique em executar no canto inferior direito.  
Pode demorar um pouco mas você saberá que conseguiu assim que ver uma tela parecida com essa.__  

![tela](https://github.com/ArturHamannRonconi/gerenciador_de_eventos/blob/master/assets/medias/tela.png)  

__Espero ter conseguido te ajudar na instalação caso tenha surgido alguma dúvida não exite em perguntar,  
crie um issue aqui mesmo no github dentro desse projeto que darei o meu máximo para te ajudar assim que for possível.__

## Como Usar
__A parte mais difícil você já passou, agora para conseguir usar o programa é simples,  
basta abrir uma aba do seu navegador e acessa esse [link](http://localhost/) digitar localhost na URL,  
agora que você acessou sua máquina clique na pasta que foi nomeada como `gerenciador_de_eventos`,  
agora esta tudo pronto para ser usar, apenas siga as perguntas que são mostradas em cima e responda clicando em uma opção.__  

## tecnologias
__Para fazer essa aplicação usei html e css com quase nada de javascript no front-end,  
meu objetivo com isso é tentar destacar ao máximo o back-end, o back-end que é feito em php puro   
sem framework algum, usando o php pude utilizar os paradigmas procedural e orientado a objetos,  
a arquitetura escolhida foi o MVC que é a que eu tenho estudado ultimamente,  
além do php também usei o banco de dados MySQL para que pudesse ser feita a persistência dos dados.__  
