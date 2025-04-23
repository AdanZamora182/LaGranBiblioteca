import pymysql

# Leer los datos de conexión desde config.php
def obtener_datos_conexion():
    with open('config.php', 'r', encoding='utf-8') as file:
        lines = file.readlines()
        config = {}
        for line in lines:
            if '=' in line and not line.strip().startswith('//'):
                key, value = line.split('=')
                config[key.strip()] = value.strip().strip('";')
        return config

config = obtener_datos_conexion()

# Conectar a la base de datos
conn = pymysql.connect(
    host=config['$servername'],
    user=config['$username'],
    password=config['$password'],
    database=config['$dbname']
)

cursor = conn.cursor()

# Consulta para obtener los títulos de los libros
query = "SELECT titulo FROM libros_difusos"

try:
    cursor.execute(query)
    resultados = cursor.fetchall()

    # Crear un archivo de texto y escribir los títulos
    with open('titulos_libros.txt', 'w', encoding='utf-8') as file:
        for row in resultados:
            file.write(row[0] + '\n')  # Escribir cada título en una línea

    print("Archivo 'titulos_libros.txt' generado correctamente.")
except Exception as e:
    print(f"Error al generar el archivo: {e}")
finally:
    cursor.close()
    conn.close()