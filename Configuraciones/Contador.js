document.addEventListener('DOMContentLoaded', () => {

    // --- Helper: convierte "$1.234" -> 1234 (entero) ---
    const precioANumero = str => {
        if(!str) return 0;
        return parseInt(String(str).replace(/\s/g,'').replace(/\$/g,'').replace(/\./g,'')) || 0;
    };

    // --- Función para actualizar contador del carrito ---
    const actualizarContadorCarrito = total => {
        const icono = document.querySelector('.fa-bag-shopping');
        if(!icono) return;

        let contador = icono.parentElement.querySelector('#contador-carrito');
        if(!contador){
            contador = document.createElement('span');
            contador.id = 'contador-carrito';
            contador.classList.add('contador-carrito');
            icono.parentElement.style.position = 'relative';
            icono.parentElement.appendChild(contador);
        }
        contador.textContent = total;
        contador.classList.add('animate');
        setTimeout(()=>contador.classList.remove('animate'), 200);
    };

    // --- Agregar al carrito desde la card ---
    document.querySelectorAll('.form-agregar-carrito').forEach(form => {
        form.addEventListener('submit', async e => {
            e.preventDefault();
            e.stopPropagation();

            if(!form.querySelector('input[name="cantidad"]')){
                const inputCant = document.createElement('input');
                inputCant.type = 'hidden';
                inputCant.name = 'cantidad';
                inputCant.value = 1;
                form.appendChild(inputCant);
            }

            const datos = new FormData(form);
            const res = await fetch('agregar_carrito.php', { method: 'POST', body: datos });
            const data = await res.json();

            actualizarContadorCarrito(data.total);

            const btn = form.querySelector('button[type="submit"]');
            if(btn){
                btn.textContent = "Añadido ✅";
                btn.style.background = "#8ccf84";
                setTimeout(() => {
                    btn.textContent = "Añadir al carrito";
                    btn.style.background = "";
                }, 1000);
            }
        });
    });

    // --- Modal al hacer click en la card ---
    document.querySelectorAll('.producto-card').forEach(card => {
        card.addEventListener('click', e => {
            if(e.target.closest('form')) return; // evita abrir modal

            const modalEl = document.getElementById('modalProducto');
            const modal = new bootstrap.Modal(modalEl);

            const nombre = card.querySelector('h4').textContent;
            const cat = card.querySelector('p').textContent;
            const precio = card.querySelector('span').textContent;
            const img = card.querySelector('img').src;
            const desc = card.dataset.des || "";
            const id = card.dataset.id || '';

            document.getElementById('modalProductoLabel').textContent = nombre;
            document.getElementById('modalProductoImg').src = img;
            document.getElementById('modalProductoImg').alt = nombre;
            document.getElementById('modalProductoCat').textContent = "Categoría: " + cat;
            document.getElementById('modalProductoDesc').textContent = desc;
            document.getElementById('modalProductoPrecio').textContent = precio;

            modalEl.dataset.productId = id;
            modal.show();
        });
    });

    // --- Animación secuencial de aparición de las cards ---
    document.querySelectorAll('.producto-card').forEach((card,index)=>{
        card.style.animationDelay = `${index*0.05}s`;
    });

    // --- Agregar al carrito desde modal ---
    const btnModal = document.querySelector('#modalProducto .btn-success');
    if(btnModal){
        btnModal.addEventListener('click', async () => {
            const modalEl = document.getElementById('modalProducto');
            const id = modalEl.dataset.productId || '';
            const nombre = document.getElementById('modalProductoLabel').textContent;
            const precioTxt = document.getElementById('modalProductoPrecio').textContent;
            const precio = precioANumero(precioTxt);
            const img = document.getElementById('modalProductoImg').src;
            const cat = document.getElementById('modalProductoCat').textContent.replace('Categoría: ','');
            const cantidad = 1;

            const datos = new FormData();
            if(id) datos.append('id', id);
            datos.append('nombre', nombre);
            datos.append('precio', precio);
            datos.append('imagen', img);
            datos.append('categoria', cat);
            datos.append('cantidad', cantidad);

            const res = await fetch('agregar_carrito.php', { method: 'POST', body: datos });
            const data = await res.json();

            actualizarContadorCarrito(data.total);

            btnModal.textContent = "Añadido ✅";
            btnModal.style.background = "#8ccf84";
            setTimeout(()=>{
                btnModal.textContent = "Añadir al carrito";
                btnModal.style.background = "";
            },1000);
        });
    }
});