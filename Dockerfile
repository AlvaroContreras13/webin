# Usar la imagen base de PHP con Apache
FROM php:8.1-apache

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    curl \
    gnupg2 \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    sudo \
    unzip \
    autoconf \
    unixodbc-dev \
    gcc \
    g++ \
    make \
    libxml2-dev \
    curl \
    libc-dev \
    pkg-config \
    gpg \
    libzip-dev \
    && pecl install pdo_sqlsrv sqlsrv \
    && docker-php-ext-enable pdo_sqlsrv sqlsrv

# Agregar la clave de Microsoft
RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg

# Agregar el repositorio de Microsoft para Debian 12
RUN curl https://packages.microsoft.com/config/debian/12/prod.list | tee /etc/apt/sources.list.d/mssql-release.list

# Actualizar la lista de paquetes después de agregar el repositorio de Microsoft
RUN apt-get update

# Instalar el controlador ODBC de Microsoft
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql18

# Opcional: instalar herramientas adicionales (bcp, sqlcmd)
RUN ACCEPT_EULA=Y apt-get install -y mssql-tools18

# Opcional: agregar mssql-tools al PATH
RUN echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc

# Opcional: instalar la librería de Kerberos (para distribuciones slim)
RUN apt-get install -y libgssapi-krb5-2

# Habilitar los módulos necesarios de Apache
RUN a2enmod rewrite
RUN service apache2 restart

# Crear el directorio uploads y ajustar los permisos
RUN mkdir -p /var/www/html/uploads
RUN chown -R www-data:www-data /var/www/html/uploads
RUN chmod -R 755 /var/www/html/uploads

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y libzip-dev

# Instalar la extensión zip de PHP
RUN docker-php-ext-install zip

# Copiar los archivos del proyecto al contenedor (ajustar según sea necesario)
COPY . /var/www/html/

# Exponer el puerto 80 para acceso HTTP
EXPOSE 80

# Comando por defecto para iniciar Apache
CMD ["apache2-foreground"]
