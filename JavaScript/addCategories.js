document.addEventListener("DOMContentLoaded", function() {
    // Fazer uma solicitação AJAX
    $.ajax({
        url: '../../Controllers/RecipeController.php',
        method: 'GET',
        dataType: 'json',
        data: {
            action: 'getCategories',
        },
        success: function(response) {
            console.log(response);
            if (response) {
                // Adicione a barra de pesquisa
                addSearchBar(response.categories);

                updateCategoryList(response.categories);
            } else {
                // Caso não haja categorias na resposta
                console.log('Nenhuma categoria encontrada.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro na solicitação de categorias:', error);
        }
    });
});

function addSearchBar(categories) {
    // Selecione o elemento onde a barra de pesquisa será adicionada
    var searchBarContainer = document.getElementById('searchBarContainer');

    if (!searchBarContainer) {
        console.error('Elemento com o ID "searchBarContainer" não encontrado.');
        return;
    }
    // Crie um elemento de entrada de texto para a barra de pesquisa
    var searchBar = document.createElement('input');
    searchBar.type = 'text';
    searchBar.placeholder = 'Pesquisar categorias';
    searchBar.classList.add('form-control');  // Adicione a classe do Bootstrap para estilizar como um controle de formulário
    searchBar.style.border = '1px solid #A97311';  // Adicione a borda com a cor desejada
    searchBar.addEventListener('input', function() {
        // Atualize a lista de categorias com base no termo de pesquisa
        updateCategoryList(categories, searchBar.value.toLowerCase());
    });

    // Adicione a barra de pesquisa ao contêiner
    searchBarContainer.appendChild(searchBar);
}


function updateCategoryList(categories, searchTerm = '') {
    var categoryListContainer = document.getElementById('categoryListContainer');
    var selectedCategoriesContainer = document.getElementById('selectedCategoriesContainer');

    if (!categoryListContainer || !selectedCategoriesContainer) {
        console.error('Elemento com o ID "categoryListContainer" ou "selectedCategoriesContainer" não encontrado.');
        return;
    }

    // Limpe as listas antes de atualizar
    categoryListContainer.innerHTML = '';
    selectedCategoriesContainer.innerHTML = '';

    // Filtrar categorias com base no termo de pesquisa
    var filteredCategories = categories.filter(function (category) {
        return category.Category_Name.toLowerCase().includes(searchTerm);
    });

    // Adicionar categorias filtradas à lista
    filteredCategories.forEach(function (category) {
        var categoryItem = document.createElement('span');
        categoryItem.style.fontSize = '120%';
        categoryItem.classList.add('badge', 'badge-secondary', 'my-1', 'mx-1', 'rounded', 'bg-style'); // Adiciona a classe 'bg-style'
        categoryItem.textContent = category.Category_Name;
        categoryItem.id = category.Category_ID; // Atribui o ID da categoria ao elemento <span>

        // Estilo de fundo desejado
        categoryItem.style.background = 'linear-gradient(103deg, rgba(91, 91, 91, 1) 0%, rgba(59, 59, 59, 1) 98%)';

        // Adiciona o item da categoria à lista
        categoryListContainer.appendChild(categoryItem);

        categoryItem.addEventListener('click', function () {
            // Move o item da categoria para o contêiner de categorias selecionadas
            selectedCategoriesContainer.appendChild(categoryItem);
        
            // Adiciona o botão "x" ao item da categoria
            var removeButton = document.createElement('button');
            removeButton.innerHTML = 'X'; // Código HTML para o símbolo "x"
            removeButton.classList.add('btn', 'btn-link', 'btn-remove', 'text-white'); // Adiciona as classes para centralizar
            removeButton.style.textDecoration = 'none'; // Remove o sublinhado
            removeButton.style.fontSize = '100%';
            removeButton.style.paddingLeft = '8px';
            removeButton.addEventListener('click', function (event) {
                // Remove o botão "X" e a categoria da lista de categorias selecionadas
                event.stopPropagation(); // Impede a propagação do evento de clique para o item da categoria
                removeButton.parentElement.removeChild(removeButton); 
                categoryListContainer.appendChild(categoryItem);
            
                // Remova o ouvinte de eventos após mover a categoria de volta
                categoryItem.removeEventListener('click', null);
            });
            
        
            // Adiciona o botão "x" ao item da categoria
            categoryItem.appendChild(removeButton);
        });
        
        // Adiciona o item da categoria à lista
        categoryListContainer.appendChild(categoryItem);

    });

    
}


function AddCategories() {
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('id');
    
    // Obter todas as categorias em selectedCategoriesContainer
    var selectedCategories = document.getElementById('selectedCategoriesContainer').querySelectorAll('span');
    
    // Extrair os IDs das categorias
    var categoryIds = Array.from(selectedCategories).map(function(category) {
        return category.id;
    });

    console.log(categoryIds);
    
    // Realizar a solicitação AJAX com os IDs das categorias
    $.ajax({
        url: '../../Controllers/RecipeController.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'setCategories',
            categoryIds: categoryIds,
            recipeId: recipeId,
        },
        success: function(response) {
            console.log(response);
            if (response) {
                window.location.href = '../../PHP/Pages/dashboard.php';
            } else {
                console.log('Nenhuma categoria encontrada.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro na solicitação de categorias:', error);
        }
    });
};


