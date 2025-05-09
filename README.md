# Financial Products API

This project is a RESTful API for managing financial products, specifically focused on credit cards. It's built using Symfony framework and follows Domain-Driven Design (DDD) principles.

## Project Structure

The project is organized following a clean architecture approach:

- `src/Controller`: Contains the API endpoints
- `src/Entity`: Domain entities
- `src/FinancialProducts`: Core business logic
  - `Application`: Application services and use cases
  - `Domain`: Business rules and domain models
  - `Infrastructure`: Technical implementations
- `src/Repository`: Data access layer

## API Endpoints

### Credit Cards

#### List Credit Cards
- **Endpoint**: `GET /api/cards`
- **Query Parameters**:
  - `page`: Page number (default: 1)
  - `limit`: Items per page (default: 10)
  - `sort_by`: Field to sort by (default: 'title')
  - `sort_order`: Sort direction ('asc' or 'desc', default: 'asc')
- **Response**: Paginated list of credit cards

#### Update Credit Card
- **Endpoint**: `PATCH /api/cards/{id}`
- **Request Body**:
  ```json
  {
    "title": "string",
    "description": "string",
    "incentiveAmount": "number",
    "cost": "number"
  }
  ```
- **Response**: Success message or error details

## Commands

### Import Credit Cards
To import credit cards from the external API, use the following command:

```bash
php bin/console app:import-credit-cards
```

This command will:
1. Connect to the external API
2. Fetch all available credit cards
3. Import or update them in the local database
4. Show success or error messages in the console

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Configure your database connection in `.env`
4. Run database migrations:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

## Development

- The project uses PHP 8.1 or higher
- Symfony 6.x framework
- Doctrine ORM for database management
- Follows PSR-12 coding standards

## Testing

To run the test suite using Codeception:

```bash
./vendor/bin/codecept run
```

To run specific test suites:
- For unit tests:
  ```bash
  ./vendor/bin/codecept run unit
  ```
- For API tests:
  ```bash
  ./vendor/bin/codecept run api
  ```

## Contributing

1. Create a new branch for your feature
2. Make your changes
3. Submit a pull request

## License

This project is licensed under the MIT License.

# Symfony 7.2 Docker Development Environment

Este proyecto proporciona un entorno de desarrollo Docker para Symfony 7.2 con PHP 8.2, MySQL 8.0 y Nginx.

## Requisitos

- Docker
- Docker Compose
- VS Code (opcional, para usar devcontainer)

## Configuración

1. Clona este repositorio
2. Abre el proyecto en VS Code
3. Si usas VS Code, presiona F1 y selecciona "Dev Containers: Reopen in Container"
4. Si no usas VS Code, ejecuta:
   ```bash
   docker-compose up -d
   ```

## Acceso

- Aplicación: http://localhost:8080
- MySQL: localhost:3306
  - Usuario: symfony
  - Contraseña: symfony
  - Base de datos: symfony
- API: https://n24-api.elvisreyes.com/
  - prueba online de la API

## Comandos útiles

```bash
# Entrar al contenedor PHP
docker-compose exec php bash

# Instalar dependencias
composer install

# Crear una nueva entidad
php bin/console make:entity

# Actualizar la base de datos
php bin/console doctrine:schema:update --force
```

## Estructura del proyecto

- `.devcontainer/`: Configuración de Docker y VS Code
- `public/`: Punto de entrada de la aplicación
- `src/`: Código fuente de la aplicación
- `config/`: Configuraciones de Symfony
- `var/`: Archivos temporales y caché 