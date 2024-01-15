## Autodialer Versión 1.2.8 estable

Sistema de llamadas **automatizadas** para asterisk.

---

## Instalación Autodialer

Pasos para una correcta instalación de autodialer:

1. Copiar la carpeta *autodialer* en tu directorio raiz.
2. Los archivos de Base de datos, configuración de contexto de llamada y AGI se encuentan en la carpeta *install*
    **Instalación de Base de datos**
    - Cree una base de datos con el nombre *autodialer* y realice la importacion del archivo *docker/dev/autodialer.sql*.
    **Configuració de Constexto de llamada**
    - Copie el contenido del archivo *install/extension_custom.txt* y peguelo al **final** del archivo */etc/asterisk/extension_custom.conf*.
    **Instalación de archivo AGI**
    - Copie el archivo *install/agi-bin/calificar.php* y peguelo en la ruta */var/lib/asterisk/agi-bin*.

    **NOTA:** *para que se apliquen los cambios es necesario realizar un* **reload** *o un* **amportal restart** *de asterisk.*

3. Es necesario dar permismo **chown 0777** a la carpeta *files_csv* para permitir el guardado de archivos .csv.

4. Debe de configurar el usuario y contraseña a la base de datos en lso siguientes archivos
    - *autodialer/conexion.php*
        ```php
        // DB credentials.
	    define('DB_NAME','autodialer');
        define('DB_HOST','localhost');
	    define('DB_USER','root');
	    define('DB_PASS','password');

	    define('DB_NAME_ASTERISK','asterisk');
        define('DB_HOST_ASTERISK','localhost');
	    define('DB_USER_ASTERISK','root');
	    define('DB_PASS_ASTERISK','password');
        ```
    - *extension_custom.conf*
        ```
        exten => 4010,3,Mysql(connect conexion localhost root password autodialer)
        ```
    - */var/lib/asterisk/agi-bin/calificar.php*
        ```php
        //Conexion BD poll
        $dbase='autodialer';
        $servidor='localhost';
        $usuario='root';
        $pass='password';
        ```
5. para comprobar que la instalación se ha realizado correctamente debe de acceder a *MI_IP/autodialer* y debe poder ver el panel de administración.

---

# Derechos reservados
**xudo.dev**
Santiago Gutierrez G.
zam.2014.sg@gmail.com
