# 📸 Photo Gallery AWS S3

Uma aplicação web moderna para gerenciamento de galeria de fotos com integração ao AWS S3, construída com Laravel e Tailwind CSS.

## ✨ Características

- 🖼️ **Upload de Imagens**: Upload seguro de imagens diretamente para o AWS S3
- 🎨 **Interface Moderna**: Design responsivo construído com Tailwind CSS
- 🔄 **Service Pattern**: Arquitetura limpa com padrão de serviços e interfaces
- 🌐 **Multilíngue**: Suporte para Português Brasileiro e Inglês
- ⚡ **Performance**: Vite para build rápido e desenvolvimento eficiente
- 🔒 **Seguro**: Validação robusta de uploads e tratamento de erros

## 🛠️ Tecnologias

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS 4, Vite 7
- **Cloud**: AWS S3 para armazenamento de imagens
- **Database**: MySQL/PostgreSQL
- **Tools**: Laravel Pint, PHPUnit, Laravel Sail

## 📋 Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18+ e npm
- MySQL/PostgreSQL
- Conta AWS com S3 configurado

## 🚀 Instalação

### 1. Clone o repositório

```bash
git clone <seu-repositorio>
cd photo-gallery
```

### 2. Instale as dependências

```bash
# Dependências PHP
composer install

# Dependências JavaScript
npm install
```

### 3. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o banco de dados

Edite o arquivo `.env` com suas credenciais:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=photo_gallery
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Configure o AWS S3

Adicione suas credenciais AWS no `.env`:

```env
AWS_ACCESS_KEY_ID=sua_access_key
AWS_SECRET_ACCESS_KEY=sua_secret_key
AWS_DEFAULT_REGION=sua_regiao
AWS_BUCKET=nome_do_bucket
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### 6. Execute as migrações

```bash
php artisan migrate
```

### 7. Compile os assets

```bash
# Desenvolvimento
npm run dev

# Produção
npm run build
```

### 8. Inicie o servidor

```bash
php artisan serve
```

A aplicação estará disponível em `http://localhost:8000`.

## ⚙️ Configuração AWS S3

### Configuração do Bucket S3

1. Crie um bucket no S3
2. Configure as permissões adequadas (política de bucket)
3. Ative o versionamento (opcional)
4. Configure CORS para permitir uploads do frontend

Exemplo de política CORS:

```json
[
    {
        "AllowedHeaders": ["*"],
        "AllowedMethods": ["GET", "PUT", "POST", "DELETE"],
        "AllowedOrigins": ["http://localhost:8000"],
        "ExposeHeaders": []
    }
]
```

## 🎯 Uso

### Upload de Imagens

1. Acesse a página principal
2. Clique em "Upload Image" ou arraste e solte arquivos
3. Adicione um título para a imagem
4. Confirme o upload

### Visualização

- As imagens são exibidas em uma galeria responsiva
- Clique em uma imagem para visualizar em tamanho completo
- Navegue entre as imagens usando os controles

## 🏗️ Arquitetura

### Estrutura do Projeto

```
app/
├── Http/Controllers/
│   └── GalleryController.php     # Controle da galeria
├── Interfaces/
│   └── ImageServiceInterface.php # Interface do serviço
├── Models/
│   ├── Image.php                 # Model da imagem
│   └── User.php                  # Model do usuário
├── Services/
│   ├── ImageServiceToFileSystem.php # Serviço local
│   └── ImageServiceToS3.php         # Serviço AWS S3
└── View/Components/
    └── Image.php                 # Componente de imagem
```

### Padrões Utilizados

- **Repository Pattern**: Para abstração do acesso aos dados
- **Service Pattern**: Para lógica de negócio
- **Interface Segregation**: Para flexibilidade na implementação
- **Dependency Injection**: Para inversão de dependência

## 🧪 Testes

Execute os testes usando PHPUnit:

```bash
# Executar todos os testes
php artisan test

# Executar testes específicos
php artisan test --filter GalleryControllerTest

# Executar com cobertura
php artisan test --coverage
```

## 📦 Deploy

### Produção com Laravel Sail

```bash
# Build da aplicação
sail up -d
sail artisan migrate --force
sail npm run build
```

### Deploy Manual

1. Configure o servidor web (Apache/Nginx)
2. Instale as dependências de produção
3. Configure as variáveis de ambiente
4. Execute as migrações
5. Otimize a aplicação

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 🆘 Suporte

Se você encontrar algum problema ou tiver dúvidas:

1. Verifique se todas as dependências estão instaladas
2. Confirme se as configurações do AWS S3 estão corretas
3. Verifique os logs da aplicação em `storage/logs/`
4. Abra uma issue no GitHub

## 📚 Recursos Úteis

- [Documentação do Laravel](https://laravel.com/docs)
- [Documentação do AWS S3](https://docs.aws.amazon.com/s3/)
- [Documentação do Tailwind CSS](https://tailwindcss.com/docs)
- [Documentação do Vite](https://vitejs.dev/)
