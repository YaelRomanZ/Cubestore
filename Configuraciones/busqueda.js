document.addEventListener('DOMContentLoaded', () => {
  const searchOverlay = document.getElementById('searchOverlay');
  const searchInput = searchOverlay.querySelector('input.form-control');
  const searchSuggestions = searchOverlay.querySelector('.search-suggestions');

  searchInput.addEventListener('input', () => {
    const query = searchInput.value.trim();

    // Si está vacío, mostrar sugerencias por defecto
    if(query === ''){
      searchSuggestions.innerHTML = `
        <a href="#">Juguetes populares</a>
        <a href="#">Ropa infantil</a>
        <a href="#">Accesorios</a>
      `;
      return;
    }

    // Llamada AJAX a busqueda.php
    fetch('busqueda.php?q=' + encodeURIComponent(query))
      .then(res => res.json())
      .then(data => {
        searchSuggestions.innerHTML = '';
        if(data.length === 0){
          searchSuggestions.innerHTML = '<span>No se encontraron resultados</span>';
          return;
        }
        data.forEach(item => {
          const a = document.createElement('a');
          a.href = 'catalogo.php?buscar=' + encodeURIComponent(item.nom);
          a.innerHTML = `<img src="${item.img}" alt="${item.nom}" style="width:40px;height:40px;object-fit:cover;margin-right:10px;">${item.nom} - $${item.precio}`;
          searchSuggestions.appendChild(a);
        });
      });
  });

  // Hacer click en sugerencias
  searchSuggestions.addEventListener('click', (e) => {
    const a = e.target.closest('a');
    if(a){
      window.location.href = a.href;
      e.preventDefault();
    }
  });
});