# CRUD de Livros

Aplicação de CRUD de livros com autenticação desenvolvida com PHP 8.1/Symfony 6 e Angular 16.

### Instalação e como usar

Primeiramente, você deve copiar o repositório para o seu computador:
```zsh
git clone https://github.com/daniortlepp/books-app.git
```

Acesse o diretório:
```zsh
cd books-app
```
Dentro do projeto estão 2 diretórios:
- book-api - API para gerenciamento de livros e autenticação
- books-crud - Frontend do gerenciamento de livros

Vamos começar pela API.

Acesse o diretório da API:
```zsh
cd book-api
```

Configure o arquivo .env:
- Inserir o usuário e senha do MySQL juntamente com o nome do banco de dados que ira usar:
```zsh
APP_ENV=dev
APP_SECRET=2d430eb59387ef97df7617f144526da2
DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name?serverVersion=5.7"
CORS_ALLOW_ORIGIN="*" (Aqui pode colocar a url do frontend)
```

- Gerar o JWT Secret Key:
Executar o comando abaixo no terminal:
```zsh
php -r 'echo base64_encode(random_bytes(32));'
```

Abrir o arquivo .env e adicionar:
```zsh
JWT_SECRET_KEY=[chave que retornar do comando acima]
```

Instalar as dependências:
```zsh
composer install
```

Criar o banco de dados:
```zsh
php bin/console doctrine:database:create
```

Criar as tabelas do banco de dados:
```zsh
php bin/console doctrine:migrations:migrate
```

Iniciar a API
```zsh
symfony server:start
```

A API está pronta para iniciar.

Agora vamos instalar o frontend:

Sair do diretório da API:
```zsh
cd ..
```

Acessar o diretório:
```zsh
cd books-crud
```

Instalar as dependências:
```zsh
npm install
```

Iniciar o CRUD:
```zsh
ng serve
```

E a aplicação está toda pronta para você utilizar!
Acesse http://localhost:4200 no seu navegador de preferência

### Sobre a aplicação

A aplicação consiste em:

- Tela de login para fazer a autenticação para poder entrar no sistema
- Cadastro de novos usuários
- Listagem de livros: uma lista com todos livros cadastrados
- Cadastro de um novo livro
- Edição dos dados de um livro
- Exclusão de um livro
