<div aling="center">
# Parfinanciero Prospectiva AI
</div>

## Descripción
Este proyecto es una aplicación desarrollada en Laravel con el objetivo de proporcionar análisis financiero prospectivo utilizando inteligencia artificial.

## Instalación y Configuración

### Requisitos Previos
- PHP >= 8.1
- Composer
- MySQL o PostgreSQL
- Node.js y npm (opcional para assets)

### Pasos para la Instalación
1. Clonar el repositorio:
   ```sh
   git clone https://github.com/BernersLeeSolutions/Parfinanciero_ProspectivaIA.git
   cd Parfinanciero_ProspectivaIA
   ```
2. Instalar dependencias:
   ```sh
   composer install
   npm install  # Opcional, si se usan assets frontend
   ```
3. Configurar variables de entorno:
   ```sh
   cp .env.example .env
   ```
   - Modificar `.env` con las credenciales de la base de datos.

4. Generar clave de la aplicación:
   ```sh
   php artisan key:generate
   ```
5. Ejecutar migraciones y seeders:
   ```sh
   php artisan migrate --seed
   ```
6. Iniciar el servidor:
   ```sh
   php artisan serve
   ```

## Estructura del Proyecto

### Directorios Principales
- **app/**: Contiene la lógica de la aplicación, incluyendo controladores, modelos, repositorios y servicios.
- **bootstrap/**: Inicialización del framework y configuración de caché.
- **config/**: Configuraciones del sistema, base de datos, autenticación, etc.
- **database/**: Migraciones, seeders y factories.
- **public/**: Punto de entrada público de la aplicación (index.php, assets).
- **resources/**: Archivos de vistas Blade, JavaScript y CSS.
- **routes/**: Definición de rutas (API, web, consola).
- **storage/**: Archivos generados por la aplicación, logs y caché.
- **tests/**: Pruebas unitarias y funcionales.

### Contenido de `app/`
- **Http/Controllers/**: Controladores para manejar las peticiones.
  - `AIAnalysisController.php`
  - `FinancialDataController.php`
  - `FinancialForecastController.php`
- **Models/**: Modelos que representan las entidades del sistema.
  - `Expense.php`
  - `Goal.php`
  - `User.php`
- **Repositories/**: Implementación de repositorios para manejo de datos.
  - `Api/ApiExpenseRepository.php`
  - `Fake/FakeExpenseRepository.php`
- **Services/**: Contiene la lógica de negocio.
  - `ExpenseService.php`
  - `FinancialForecastService.php`
  - `OpenAIService.php`

### Contenido de `database/`
- **migrations/**: Archivos de migraciones para la base de datos.
  - `2025_01_20_163235_create_expenses_table.php`
  - `2025_01_20_163314_create_goals_table.php`
- **seeders/**: Datos de prueba para poblar la base de datos.
  - `ExpenseSeeder.php`
  - `GoalSeeder.php`

Cada carpeta y archivo tiene una función específica dentro del desarrollo de la aplicación. Esta estructura modular permite una mejor organización y mantenimiento del código.

## Despliegue en Producción

### Requisitos Previos
- Un servidor con soporte para PHP y MySQL/PostgreSQL.
- Servidor web como Apache o Nginx.
- Certificado SSL (recomendado para producción).

### Pasos para el Despliegue
1. **Subir los archivos al servidor**
   ```sh
   scp -r * usuario@servidor:/ruta/del/proyecto
   ```
2. **Configurar el entorno**
   - Copiar el archivo `.env.example` a `.env` y actualizar las credenciales de la base de datos y demás configuraciones.
3. **Instalar dependencias en el servidor**
   ```sh
   composer install --no-dev --optimize-autoloader
   ```
4. **Ejecutar migraciones y seeders**
   ```sh
   php artisan migrate --seed --force
   ```
5. **Configurar permisos de archivos**
   ```sh
   chmod -R 775 storage bootstrap/cache
   ```
6. **Configurar el servidor web** (Ejemplo Nginx):
   ```nginx
   server {
       listen 80;
       server_name tu-dominio.com;
       root /ruta/del/proyecto/public;
       index index.php index.html;
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       location ~ \.php$ {
           include fastcgi_params;
           fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
       }
   }
   ```
7. **Generar la caché de configuración**
   ```sh
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
8. **Configurar tareas programadas** (Cron Job):
   ```sh
   crontab -e
   ```
   Agregar la siguiente línea:
   ```sh
   * * * * * php /ruta/del/proyecto/artisan schedule:run >> /dev/null 2>&1
   ```
9. **Reiniciar servicios**
   ```sh
   sudo systemctl restart nginx
   sudo systemctl restart php8.1-fpm
   ```

Con estos pasos, la aplicación estará desplegada en producción con un rendimiento y seguridad óptimos.