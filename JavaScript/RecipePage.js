// script.js

document.addEventListener("DOMContentLoaded", function() {
    // Fazer uma solicitação AJAX
    $.ajax({
        url: '../../Controllers/RecipeController.php?action=getRecipesById',
        method: 'GET',
        dataType: 'json',
        data: {
            recipeId: 1 // Substitua 1 pelo valor real do ID da receita desejada
        },
        success: function(response) {
            // Seu código de manipulação de sucesso aqui
        },
        error: function(xhr, status, error) {
            console.error('Erro na solicitação de receitas:', error);
        }
    });
    
});
