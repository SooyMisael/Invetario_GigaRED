document.addEventListener("DOMContentLoaded", function(){
    const formularios_ajax = document.querySelectorAll(".FormularioAjax");

    formularios_ajax.forEach(form => {
        form.addEventListener("submit", function(e){
            e.preventDefault(); // Evita envío normal

            const data = new FormData(this);
            const action = this.getAttribute("action");
            const method = this.getAttribute("method");

            fetch(action, { method: method, body: data })
                .then(res => res.json())
                .then(respuesta => {
                    console.log("Respuesta del servidor:", respuesta); 
                    if(respuesta.success && respuesta.redirect){
                        window.location.href = respuesta.redirect.replace(/\\/g,'');
                        return;
                    }
                })
                .catch(err => console.error("Error AJAX:", err));
        });
    });
});


