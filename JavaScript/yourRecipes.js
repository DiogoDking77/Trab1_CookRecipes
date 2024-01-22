document.addEventListener("DOMContentLoaded", function() {
    

    // Fazer uma solicitação AJAX
    $.ajax({
        url: '../../Controllers/RecipeController.php',
        method: 'GET',
        dataType: 'json',
        data: {
            action: 'getYourRecipes',
            userId: userIdFromPHP,
        },
        success: function(response) {
            
            // Limpar a lista de receitas
            $("#recipesList").empty();

            // Verificar se há receitas na resposta
            if (response && response.recipes && response.recipes.length > 0) {
                // Contador para controlar o carregamento de imagens
                var imagesLoaded = 0;

                // Função para adicionar o card à lista após o carregamento de todas as imagens
                function addCardToDOM(cardHtml) {
                    $("#recipesList").append(cardHtml);
                    imagesLoaded++;

                    // Se todas as imagens foram carregadas, ajuste a altura conforme necessário
                    if (imagesLoaded === response.recipes.length) {
                        adjustCardHeight();
                    }
                }

                // Função para ajustar a altura dos cards
                function adjustCardHeight() {
                    $(".card").each(function(index, card) {
                        // Ajuste a altura do card com base na altura da imagem
                        var imageHeight = $(card).find('.card-img-top').height();
                        $(card).css('height', imageHeight + $(card).find('.card-body').height());
                    });
                }

                function generateCategoryTags(categories) {
                    var categoryTags = '';
                    for (var i = 0; i < categories.length; i++) {
                        categoryTags += '<span class="badge badge-secondary mx-1 rounded bg-black" style="font-size: 50%;">' + categories[i].Category_Name + '</span>';
                    }
                    return categoryTags;
                }

                // Adicionar as receitas à lista
                $.each(response.recipes, function(index, recipe) {
                    var imgElement;

    // Verificar se há fotos na receita
                    if (recipe.photos && recipe.photos.length > 0) {
                        // Se houver fotos, use a primeira foto
                        imgElement = $('<img>').attr('src', 'data:image/jpeg;base64,' + recipe.photos[0].Photo)
                            .addClass('card-img-top center-cropped')
                            .attr('alt', recipe.Recipe_Name)
                            .css({
                                'object-fit': 'cover',
                                'width': '100%',
                                'height': '40%', // Ajuste a altura conforme necessário
                                'object-position': 'center center' // Ajuste a posição conforme necessário
                            });
                    } else {
                        // Se não houver fotos, use um ícone de imagem padrão
                        imgElement = $('<i>').addClass('fas fa-image fa-5x text-secondary d-flex justify-content-center align-items-center')
                            .css({
                                'width': '100%',
                                'height': '40%', // Ajuste a altura conforme necessário
                            });
                    }
                
                    // Criar o card com a imagem e o título
                    var cardTitle = $('<h5>').addClass('card-title overflow-hidden').text(recipe.Recipe_Name)
                        .css({
                            'white-space': 'nowrap',
                            'overflow': 'hidden',
                            'text-overflow': 'ellipsis',
                            'height' : '10%'
                        });
                
                    // Gerar tags de categoria
                    var categoryTags = generateCategoryTags(recipe.categories);
                
                    // Criar contêiner para as tags de categoria com rolagem horizontal
                    var categoryContainer = $('<div>').addClass('category-container d-flex flex-nowrap overflow-auto mb-1 mt-1').css({'width': '100%', 'height' : '5%'});
                    categoryContainer.append(categoryTags);
                
                    // Adicionar o card completo (sem a descrição)
                    var card = $('<div>').addClass('card p-2 mb-1 mx-1 shadow').css({'width': '30vw', 'min-width': '30vw', 'height': '40vh', 'background-color': '#f2f2f2'})
                    .append(imgElement, cardTitle, categoryContainer);
                
                    // Adicionar a descrição ao card (após os outros elementos)
                    var cardDescription = $('<p>').addClass('card-text').text(recipe.Recipe_Description)
                        .css({
                            'overflow': 'hidden',
                            'text-overflow': 'ellipsis',
                            'height' : '40%'
                        });
                
                    card.append(cardDescription);

                    card.click(function () {
                        // Redirecionar para a página RecipePage.php com o ID da receita
                        window.location.href = '../../PHP/Pages/RecipePage.php?id=' + recipe.Recipe_ID;
                    });
                
                    // Adicionar o card à lista após o carregamento de todas as imagens
                    addCardToDOM(card);
                });                
            } else {
                // Caso não haja receitas na resposta
                var noRecipesMessage = '<p class="text-muted">Nenhuma receita encontrada.</p>';
                $("#recipesList").append(noRecipesMessage);
            }
            
        },
        error: function(xhr, status, error) {
            console.error('Erro na solicitação de receitas favoritas:', error);
            
        }
    });

});

function redirectSearch() {
    var searchTerm = document.getElementById("searchInput").value;
    if (searchTerm.trim() !== "") {
        window.location.href = '../../PHP/Pages/SearchRecipes.php?search=' + searchTerm;
    }
    return false;  // Prevents the form from submitting via traditional means
}