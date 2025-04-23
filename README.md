# Instalación de Librerias de Python 

Para utilizar los scripts de Python: leerJson.py y exportar_titulos.py tienes que importar la siguiente libreria en la carpeta del proyecto: 

```bash
pip install pymysql
```

- El archivo *leerJson.py* ejecuta un script para ingresar a la base de datos los libros contenidos en *libros1.json*
- El archivo *exportar_titulos.py* genera un txt con todos los titulos de los libros que se encuentran en la base de datos

# Modificaciones del código original con respecto al proyecto de Inteligencia Artificial
 - _encuestaRecomendacion.php_: Es un formulario que implementa una encuesta con logica difusa para poder brindarle al usuario 5 recomendaciones basadas en sus preferencias seleccionadas.

 - _recomendar_libro.php_: Funge como el backend del formulario y se encarga de implementar la logica difusa para establecer las recomendaciones.

- _mostrar_recomendaciones.php_: Es la interfaz que muestra los 5 libros recomendados además de desplegar una ficha con la información respectiva de cada libro al hacer click en el, esto usando la API de Google Books para obtener la iniformación.

- ### Consideraciones importantes: 
  1. Hasta el momento la base de datos cuenta con 250 libros cons sus ponderaciones difusas correspondientes (sin que existir duplicados).
  2. Se implemento una nueva tabla en la base de datos llamada *libros_difusos* la cual contiene el titulo, autor y las respetcivas ponderaciones difusas para cada libro.
  3. Si se quieren insertar más libros se pueden utilizar los scripts de python para ingresar un prompt que brinde más libros en formato json establecido y se puede utilizar el txt de los libros que ya hay para evitar duplicados.
  4. Para la base de datos se esta utilizando Xampp con una base de datos llamada *recomendaciones_libros*


