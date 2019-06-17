## Autodialer Versión 1.2.8 estable

Sistema de llamadas **automatizadas** para asterisk.

---

## Instalación Autodialer

Pasos para una correcta instalación de autodialer:

1. Copiar la carpeta *autodialer* en tu directorio raiz.
2. Los archivos de Base de datos, configuración de contexto de llamada y AGI se encuentan en la carpeta *install*
    **Instalación de Base de datos**
    - Cree una base de datos con el nombre *autodialer* y realice la importacion del archivo *install/query/autodialer.sql*.
    **Configuració de Constexto de llamada**
    - Copie el contenido del archivo *install/extension_custom.txt* y peguelo al **final** del archivo */etc/asterisk/extension_custom.conf*.
    **Instalación de archivo AGI**
    - Copie el archivo *install/agi-bin/calificar.php* y peguelo en la ruta */var/lib/asterisk/agi-bin*.

    **NOTA:** *para que se apliquen los cambios es necesario realizar un* **reload** *o un* **amportal restart** *de asterisk.*

3. Es necesario dar permismo **chown 0777** a la carpeta *files_csv* para permitir el guardado de archivos .csv.

4. Debe de configurar el usuario y contraseña a la base de datos en lso siguientes archivos
    - *autodialer/conexion.php*
        // DB credentials.
	    define('DB_HOST','localhost');
	    define('DB_USER','root');
	    define('DB_PASS','bcga1303');
	    define('DB_NAME','autodialer');
    - *extension_custom.conf*
        exten => 4010,3,Mysql(connect conexion localhost root bcga1303 autodialer)
    - */var/lib/asterisk/agi-bin/calificar.php*
        //Conexion BD poll
        $dbase='autodialer';
        $servidor='localhost';
        $usuario='root';
        $pass='bcga1303';
5. para comprobar que la instalación se ha realizado correctamente debe de acceder a *MI_IP/autodialer* y debe poder ver el panel de administración.

---

#Derechos reservados
**xudo.dev**
Santiago Gutierrez G.
Developer semi senior
zam.2014.sg@gmail.com
