import json
import pymysql
from phpserialize import loads

# Cargar el archivo JSON
with open('libros1.json', 'r', encoding='utf-8') as file:
    libros = json.load(file)

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

# Insertar los libros en la base de datos
query = """
INSERT INTO libros_difusos (
    titulo, autor, accion, romance, misterio, fantasia, filosofia, ritmo,
    cienciaficcion, reflexion, emocional, longitud, juvenil, biografia,
    historico, mensaje, suspenso, humor, critica, finalabierto
) VALUES (
    %(titulo)s, %(autor)s, %(accion)s, %(romance)s, %(misterio)s, %(fantasia)s, %(filosofia)s, %(ritmo)s,
    %(cienciaficcion)s, %(reflexion)s, %(emocional)s, %(longitud)s, %(juvenil)s, %(biografia)s,
    %(historico)s, %(mensaje)s, %(suspenso)s, %(humor)s, %(critica)s, %(finalabierto)s
)
"""

try:
    for libro in libros:
        cursor.execute(query, libro)
    conn.commit()
    print("Libros insertados correctamente.")
except Exception as e:
    print(f"Error al insertar libros: {e}")
finally:
    # Cerrar el cursor y la conexión en el bloque `finally`
    cursor.close()
    conn.close()