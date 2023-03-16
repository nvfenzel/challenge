Challenge***
#### Índice:
1. Instrucciones de juego.
2. Instalación.
3. Instrucciones de uso.

***
# World of HIT (WoH) 
## _Bienvenidos a World of HIT A.K.A. WoH._

### 1. Instrucciones de juego.

#### Jugador


Cada jugador tiene:
- Nombre
- Email
- Tipo
    -   Humano
     -    Zombie
    
Al crearse un jugador comienza con heart 100 puntos de vida.

Si el jugador no tiene items, por defecto tiene 5 puntos de ataque dagger y 5 puntos de defensa shield.

Los puntos de ataque dagger de un jugador están dados por sus 5 puntos + la sumatoria de puntos ataque de sus items.

Los puntos de defensa shield de un jugador están dados por sus 5 puntos + la sumatoria de puntos de defensa de sus items.


#### Cada item tiene:
-  Nombre
-  Tipo
   -    Bota
    - Armadura
    - Arma

Cantidad de puntos de defensa shield. Pueden ser 0.

Cantidad de puntos de ataque dagger. Pueden ser 0.

Un jugador puede tener equipado solo un item de cada tipo, pero puede tener un inventario con todos los items que quiera.

#### Ataque

- Existen tres tipos de ataque:
    - Cuerpo a cuerpo. Daño total = Puntos de ataque.
    - A distancia. Daño total = Puntos de ataque * 0.8.
    - Ulti. Daño total = Puntos de ataque x 2.
    
Cada ataque le resta vida al otro jugador. La cantidad de vida que pierde el defensor es Daño total ataque - Puntos de defensa del defensor.

Como minimo un ataque saca 1 punto de vida al defensor.

Para tirar la Ulti el último ataque tuvo que haber sido un ataque cuerpo a cuerpo.

No se puede atacar a jugadores que ya están muertos.


 ***
 
 ### 2. Instalación.
 
 Para instalarlo primero hay que clonar el repositorio a la computadora utilizando la consola, ejecutando el siguiente comando:
 
 > git clone https://github.com/nvfenzel/challenge.git
 
 Además hay que instalar todas las dependencias con _composer_ :
 
 > composer install
 
 Luego nos movemos dentro de la carpeta con el siguiente comando:
 
 > cd challenge/

Una vez hecho esto creamos el enviroment el archivo .env de la siguiente manera:

> cp .env.example .env

Se crean las key de seguridad:

> php artisan key:generate

Luego se realizan las migraciones:

> php artisan migrate

Se ejecutan los seeders:

> php artisan migrate --seed

Se instalan los paquetes de _npm_ :

> npm install

Por último para que el juego corra hay que ejecutar

> php artisan serve

y en otra consola

> npm run dev

***

### 3. Instrucciones de uso.

Una vez seguido las instrucciones de instalación, se creará el administrador a través de los seeders. En los seeders están comendatos todos los campos de creación de todas las tablas. Para que se creen todos los datos hay que descomentarlas (en el archivo DatabaseSeeder.php se encuentran) y volver a correr el seeder, pero para ir explorando todas las rutas se dejaron comentadas.

Antes de comenzar hay que tener en cuenta que todas las rutas están protegidas tanto para el administrador como para los jugadores, por lo que en cada consulta hay que poner el token de seguridad que se recibe al ejecutar un POST a la ruta _/login_.

El primer paso sería hacer una consulta POST a la ruta _localhost:8000/api/login_ con las credenciales del administrador (_email_: admin@admin.com, _password_: admin1234) y almacenar el token para todas las consultas del administrador. Los jugadores tienen que estar autorizados por el administrador para comenzar a hacer las consultas.

Luego habria que crear el primer jugador haciendo una consulta POST a la ruta _localhost:8000/api/register_ con: _email_, _nombre_, _password_, _
password_confirmation y _type_ (que puede ser _human_ o _zombie_). Una vez hecho esto hay que hacer las consultas como jugador utilizando el _token_ que se va a generar con el registro.

Es recomendable crear otro jugador para realizar los ataques.

El resto de las rutas son las siguientes:

- Administrador:
        
    - Lista todos los items
        - GET localhost:8000/api/items
    - Agrega un nuevo Item 
        - POST localhost:8000/api/new_item con los parámetros: type (que puede ser armadura, bota o arma), pt_defense (puntos de defensa de 0 a 100), pt_attack (puntos de ataque de 0 a 100) y name (nombre del arma).
    - Autorización de jugadores
        - POST localhost:8000/api/autorization con los parámetros: id (del jugador que quiero activar) y status (solo toma los valores activo o inactivo).
    - Listar todos los jugadores
        - GET localhost:8000/api/all_players
    - Editar un Item
        - POST localhost:8000/api/edit_item con los parámetros: name, pt_defense, pt_attack y el id del item que quiero modificar.

- Jugador:
        
    - Lista todos los jugadores a los que puedo atacar
        - GET localhost:8000/api/players_active
    - Ataco a un jugador
        - POST localhost:8000/api/player_atack con los parámetros: id (del jugador al que voy a atacar) y type (que puede ser cuerpo, distancia o ulti).
    - Listar todos los items disponibles
        - GET localhost:8000/api/items
    - Listar mi inventario
        - GET localhost:8000/api/stock
    - Agregar un item a mi inventario
        - POST localhost:8000/api/add_item con los parámetros: id (del item que quiero agregar a mi inventario)
    - Ponerme ropa de mi inventario
        - POST localhost:8000/api/add_outfit con los parámetros: id (del item que tengo en mi inventario que me quiero poner)
    - Mostrarme mi ropa
        - GET localhost:8000/api/show_outfit