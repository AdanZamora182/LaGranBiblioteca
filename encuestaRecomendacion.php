<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recomendación de Libros</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
      max-width: 900px;
      margin: 0 auto;
      padding: 30px 20px;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      color: #333;
      line-height: 1.6;
    }
    
    h2 {
      text-align: center;
      color: #2c3e50;
      font-weight: 500;
      font-size: 2.2em;
      margin-bottom: 30px;
      position: relative;
      padding-bottom: 10px;
    }
    
    h2:after {
      content: '';
      position: absolute;
      width: 60px;
      height: 3px;
      background: #3498db;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
    }
    
    .container {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.18);
    }
    
    .pregunta {
      margin-bottom: 22px;
      padding: 18px;
      background: rgba(255, 255, 255, 0.7);
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .pregunta:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    }
    
    label {
      font-weight: 500;
      display: block;
      margin-bottom: 12px;
      color: #34495E;
      font-size: 1.05em;
    }
    
    .range-container {
      position: relative;
      padding: 10px 0 25px 0;
    }
    
    input[type=range] {
      -webkit-appearance: none;
      appearance: none;
      width: 100%;
      height: 8px;
      background: linear-gradient(to right, #e0e0e0, #3498db);
      border-radius: 10px;
      outline: none;
      margin: 15px 0;
      position: relative;
    }
    
    input[type=range]::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 24px;
      height: 24px;
      background: #3498db;
      border-radius: 50%;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 0 5px rgba(0,0,0,0.2);
    }
    
    input[type=range]::-webkit-slider-thumb:hover {
      background: #2980b9;
      transform: scale(1.1);
    }
    
    .range-marks {
      display: flex;
      justify-content: space-between;
      margin-top: 5px;
    }
    
    .range-marks span {
      position: relative;
      display: inline-block;
      width: 3px;
      height: 10px;
      background: #bdc3c7;
    }
    
    .range-labels {
      display: flex;
      justify-content: space-between;
      margin-top: 5px;
      font-size: 12px;
      color: #7f8c8d;
    }
    
    .valor {
      display: inline-block;
      width: 40px;
      text-align: center;
      font-weight: 600;
      color: #fff;
      background: #3498db;
      border-radius: 20px;
      padding: 3px 8px;
      position: relative;
      transition: all 0.3s ease;
    }
    
    .form-footer {
      text-align: center;
      margin-top: 35px;
    }
    
    button {
      padding: 14px 32px;
      font-size: 16px;
      font-weight: 500;
      color: #fff;
      background: linear-gradient(135deg, #3498db, #2980b9);
      border: none;
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
    }
    
    button:hover {
      background: linear-gradient(135deg, #2980b9, #3498db);
      box-shadow: 0 6px 15px rgba(52, 152, 219, 0.4);
      transform: translateY(-2px);
    }
    
    button:active {
      transform: translateY(1px);
    }
    
    .home-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #2c3e50;
      text-decoration: none;
      font-weight: 500;
    }
    
    .home-link:hover {
      color: #3498db;
    }
    
    /* Estilos para los botones de navegación */
    .back-to-home-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      color: #fff;
      background-color: #ff8c00;
      padding: 10px;
      border-radius: 50%;
      text-decoration: none;
      border: none;
      transition: background-color 0.3s ease, transform 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none; /* Quitar subrayado */
    }
    
    .back-to-home-btn:hover {
      background-color: #e67e22 !important;
      transform: scale(1.1);
      color: #fff;
      text-decoration: none; /* Quitar subrayado */
    }
    
    .back-to-home-btn i {
      font-size: 20px;
    }
  </style>
</head>
<body>
  <a href="home.php" class="back-to-home-btn">
    <i class="fas fa-home"></i>
  </a>
  
  <h2>Encuesta de Recomendación de Libros</h2>
  <div class="container">
    <form id="formularioLibro">
      <div class="pregunta">
        <label>1. ¿Qué tanto te gusta la acción?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="accion" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>

      <div class="pregunta">
        <label>2. ¿Qué tanto te interesa el romance?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="romance" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>3. ¿Qué tanto disfrutas el misterio?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="misterio" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>4. ¿Qué tanto te gustan los libros de fantasía?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="fantasia" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>5. ¿Qué tanto te gustan los libros filosóficos?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="filosofia" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>6. ¿Prefieres un ritmo narrativo lento o rápido?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="ritmo" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Lento</span>
            <span></span>
            <span>Medio</span>
            <span></span>
            <span>Rápido</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>7. ¿Qué tanto te interesa la ciencia ficción?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="cienciaficcion" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>8. ¿Te gustan los libros que te hacen reflexionar?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="reflexion" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>9. ¿Qué tanto disfrutas las historias con desarrollo emocional?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="emocional" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>10. ¿Prefieres libros cortos o largos?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="longitud" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Corto</span>
            <span></span>
            <span>Medio</span>
            <span></span>
            <span>Largo</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>11. ¿Qué tanto te gusta la literatura juvenil?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="juvenil" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>12. ¿Qué tanto te gustan las historias reales o biográficas?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="biografia" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>13. ¿Te interesan los libros históricos?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="historico" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>14. ¿Qué tan importante es para ti que el libro tenga un mensaje positivo?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="mensaje" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>15. ¿Qué tanto disfrutas el suspenso?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="suspenso" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>16. ¿Qué tanto te atraen los libros de humor?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="humor" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>
      
      <div class="pregunta">
        <label>17. ¿Qué tanto te gustan los libros con crítica social?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="critica" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>

      <div class="pregunta">
        <label>18. ¿Qué tan dispuesto estás a leer libros con finales abiertos?</label>
        <div class="range-container">
          <input type="range" min="0" max="1" step="0.1" name="finalabierto" oninput="mostrarValor(this)">
          <span class="valor">0.5</span>
          <div class="range-marks">
            <span></span><span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span><span></span>
            <span></span>
          </div>
          <div class="range-labels">
            <span>Nada</span>
            <span>Poco</span>
            <span>Medio</span>
            <span>Bastante</span>
            <span>Mucho</span>
          </div>
        </div>
      </div>

      <div class="form-footer">
        <button type="submit">Obtener recomendación</button>
      </div>
    </form>
  </div>
  <a href="home.php" class="home-link">Volver al inicio</a>

  <script>
    // Initialize all sliders to default value
    window.onload = function() {
      const sliders = document.querySelectorAll('input[type="range"]');
      sliders.forEach(slider => {
        mostrarValor(slider);
      });
    }
    
    function mostrarValor(rango) {
      const valor = parseFloat(rango.value);
      const valorElement = rango.nextElementSibling;
      valorElement.textContent = valor.toFixed(1);
      
      // Cambiar color según el valor
      const hue = (valor * 120).toString(10);
      valorElement.style.background = `hsl(${hue}, 70%, 45%)`;
      
      // Posicionar el indicador según el valor de la barra
      const posicion = valor * 100;
      valorElement.style.left = `calc(${posicion}% - 20px)`;
    }

    document.getElementById("formularioLibro").addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    const perfilUsuario = {};
    for (const [clave, valor] of formData.entries()) {
        perfilUsuario[clave] = parseFloat(valor);
    }

    // Guardar perfil en localStorage
    localStorage.setItem("perfilUsuario", JSON.stringify(perfilUsuario));

    // Redirigir a la página de recomendaciones
    window.location.href = "mostrar_recomendaciones.php";
  });
  </script>
</body>
</html>
