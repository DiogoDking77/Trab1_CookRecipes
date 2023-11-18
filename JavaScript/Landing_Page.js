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
  
    document.querySelectorAll(".recipe-card").forEach((recipeCard) => {
        recipeCard.addEventListener("mouseenter", () => {
          const description = recipeCard.querySelector("p").textContent;
      
          // Cria o pop-up
          const popup = document.createElement("div");
          popup.classList.add("recipe-card-popup");
          popup.textContent = description;
      
          // Posiciona o pop-up
          popup.style.top = recipeCard.offsetTop;
          popup.style.left = recipeCard.offsetLeft;
      
          // Adiciona o pop-up ao DOM
          document.body.appendChild(popup);
        });
      });
      

      
    window.addEventListener('scroll', function() {
      toggleHeaderVisibility();
    });
  });
  