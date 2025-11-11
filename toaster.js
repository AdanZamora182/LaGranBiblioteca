/**
 * Sistema de Toaster para La Gran Biblioteca
 * Utiliza Bootstrap 5 Toast component para mostrar mensajes estilizados
 */

/**
 * Muestra un toast con el mensaje y tipo especificado
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo de mensaje: 'success', 'error', 'warning', 'info'
 * @param {number} duration - Duración en milisegundos (por defecto 5000)
 */
function showToast(message, type = 'info', duration = 5000) {
    // Crear el contenedor de toasts si no existe
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Definir colores e iconos según el tipo
    const types = {
        success: {
            bgClass: 'bg-success',
            icon: '<i class="bi bi-check-circle-fill me-2"></i>',
            title: 'Éxito'
        },
        error: {
            bgClass: 'bg-danger',
            icon: '<i class="bi bi-x-circle-fill me-2"></i>',
            title: 'Error'
        },
        warning: {
            bgClass: 'bg-warning',
            icon: '<i class="bi bi-exclamation-triangle-fill me-2"></i>',
            title: 'Advertencia'
        },
        info: {
            bgClass: 'bg-info',
            icon: '<i class="bi bi-info-circle-fill me-2"></i>',
            title: 'Información'
        }
    };

    const toastType = types[type] || types.info;

    // Crear el elemento toast
    const toastId = 'toast-' + Date.now();
    const toastHTML = `
        <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${toastType.bgClass} text-white">
                ${toastType.icon}
                <strong class="me-auto">${toastType.title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;

    // Agregar el toast al contenedor
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);

    // Inicializar y mostrar el toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: duration
    });

    toast.show();

    // Eliminar el toast del DOM después de que se oculte
    toastElement.addEventListener('hidden.bs.toast', function () {
        toastElement.remove();
    });
}

/**
 * Funciones de atajo para tipos específicos de toast
 */
function showSuccessToast(message, duration = 5000) {
    showToast(message, 'success', duration);
}

function showErrorToast(message, duration = 5000) {
    showToast(message, 'error', duration);
}

function showWarningToast(message, duration = 5000) {
    showToast(message, 'warning', duration);
}

function showInfoToast(message, duration = 5000) {
    showToast(message, 'info', duration);
}
