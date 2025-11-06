document.addEventListener("DOMContentLoaded", () => {
  const searchIcon = document.getElementById("searchDropdown")
  const overlay = document.getElementById("searchOverlay")
  const mainContent = document.getElementById("mainContent")
  const navbar = document.querySelector("nav.navbar")

  function openSearch() {
    if (overlay) overlay.classList.add("show")
    if (mainContent) mainContent.classList.add("blurred")
  }

  function closeSearch() {
    if (overlay) overlay.classList.remove("show")
    if (mainContent) mainContent.classList.remove("blurred")
  }

  // ----------- Buscador -----------
  if (searchIcon) {
    searchIcon.addEventListener("click", (e) => {
      e.preventDefault()
      if (overlay && overlay.classList.contains("show")) {
        closeSearch()
      } else {
        openSearch()
      }
    })
  }

  if (overlay) {
    overlay.addEventListener("click", (e) => {
      if (e.target === overlay) {
        closeSearch()
      }
    })
  }

  function handleMouseLeaveSearch(e) {
    const toElement = e.relatedTarget
    if (navbar && !navbar.contains(toElement) && overlay && !overlay.contains(toElement)) {
      closeSearch()
    }
  }

  if (navbar) navbar.addEventListener("mouseleave", handleMouseLeaveSearch)
  if (overlay) overlay.addEventListener("mouseleave", handleMouseLeaveSearch)

  // ----------- Dropdowns de menú (Catálogo, Acerca de y Usuario) -----------
  const menuDropdowns = Array.from(document.querySelectorAll(".nav-item.dropdown")).filter((drop) =>
    drop.querySelector(".dropdown-toggle"),
  )

  menuDropdowns.forEach((drop) => {
    const toggle = drop.querySelector(".dropdown-toggle")
    const menu = drop.querySelector(".dropdown-menu")

    // Mostrar/ocultar al click
    toggle.addEventListener("click", (e) => {
      e.preventDefault()
      menu.classList.toggle("show")
    })

    // Cerrar cuando el mouse sale del dropdown
    drop.addEventListener("mouseleave", () => {
      menu.classList.remove("show")
    })
  })
})
