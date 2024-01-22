document.addEventListener("DOMContentLoaded", function() {
    let header = document.querySelector('.tab-bar');
    let navTabs = document.querySelector('.nav-tabs');
    let isHamburgerMenuActive = false;
  
    function toggleHeaderVisibility() {
      let currentScrollPos = window.scrollY;
  
      if (currentScrollPos === 0) {
        header.classList.add('hidden');
        // Restaura o estado do menu de hambúrguer ao rolar para o topo
        if (isHamburgerMenuActive) {
          navTabs.classList.remove('active');
          isHamburgerMenuActive = false;
        }
      } else {
        header.classList.remove('hidden');
      }
    }
  
    window.toggleNavTabs = function() {
      navTabs.classList.toggle('active');
      isHamburgerMenuActive = !isHamburgerMenuActive;
  
      // Adicione a lógica para ocultar a barra de navegação quando voltar ao topo
      if (!isHamburgerMenuActive) {
        header.classList.remove('hidden');
      }
    };
  
    // Chame a função ao carregar a página para ocultar o cabeçalho inicialmente
    toggleHeaderVisibility();
  
      

      
    window.addEventListener('scroll', function() {
      toggleHeaderVisibility();
    });
  });
  
  function redirectSearch() {
    var searchTerm = document.getElementById("searchInput").value;
    if (searchTerm.trim() !== "") {
        window.location.href = '../../PHP/Pages/SearchRecipes.php?search=' + searchTerm;
    }
    return false;  // Prevents the form from submitting via traditional means
}