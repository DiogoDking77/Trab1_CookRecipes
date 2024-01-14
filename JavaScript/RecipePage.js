


document.addEventListener("DOMContentLoaded", function() {
    // Fazer uma solicitação AJAX
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('id');

    $.ajax({
        url: '../../Controllers/RecipeController.php?action=getRecipesById',
        method: 'GET',
        dataType: 'json',
        data: {
            recipeId: recipeId,
            userId: userIdFromPHP // Substitua 1 pelo valor real do ID da receita desejada
        },
        success: function(response) {
            displayRecipeDetails(response.recipe);
        },
        error: function(xhr, status, error) {
            console.error('Erro na solicitação de receitas:', error);
        }
    });

    
});

function generateCategoryTags(categories) {
    var categoryTags = '';
    for (var i = 0; i < categories.length; i++) {
        categoryTags += '<span class="badge badge-secondary mx-1 rounded badge badge-secondary my-1 mx-1 rounded bg-style" style="font-size: 75%; background: linear-gradient(103deg, rgba(91, 91, 91, 1) 0%, rgba(59, 59, 59, 1) 98%);">' + categories[i].Category_Name + '</span>';
    }
    return categoryTags;
}

function editarReceita(recipeId, userId) {
    // Redirecionar para a página com os parâmetros necessários
    window.location.href = '../../PHP/Pages/editRecipe.php?recipeId=' + recipeId + '&userId=' + userId;
}

function displayRecipeDetails(recipe) {

// Adiciona os botões condicionalmente
    var buttonsHtml = '';
    if (recipe.isFavorited) {
        // Se for true, definir a classe do ícone como "fas fa-heart"
        buttonsHtml += '<button class="btn btn-outline-light border-0 opacity-100  text-danger favorite" onclick="toggleFavorite(this)"><i class="fas fa-heart"></i></button>';
    } else {
        // Se for false, definir a classe do ícone como "far fa-heart"
        buttonsHtml += '<button class="btn btn-outline-light border-0 opacity-100  text-danger" onclick="toggleFavorite(this)"><i class="far fa-heart"></i></button>';
    }
    buttonsHtml += '<button class="btn btn-outline-light border-0"><i class="fas fa-share text-primary"></i></button>';    
    if (userIdFromPHP && userIdFromPHP === recipe[0].User_ID) {
        var urlParams = new URLSearchParams(window.location.search);
        var recipeId = urlParams.get('id');

        buttonsHtml += '<button class="btn btn-outline-success" onclick="editarReceita(' + recipeId+ ',' + userIdFromPHP + ')">Editar</button>';
    }
    
    
    

    

    // Detalhes da receita
    var instructions = recipe[0].Recipe_Instructions.replace(/\n/g, '<br>'); // Substitui \n por <br>

    // Formatação da Data de Criação
    var creationDate = new Date(recipe[0].Creation_Date);
    var formattedCreationDate = `${('0' + (creationDate.getMonth() + 1)).slice(-2)}/
                                 ${('0' + creationDate.getDate()).slice(-2)}/
                                 ${creationDate.getFullYear()}`;

    var ingredientsTable = '<h5>Ingredientes</h5><table class="table" ><thead><tr><th>Nome</th><th>Quantidade</th></tr></thead><tbody>';
    ingredientsTable += recipe.ingredients.map(ingredient => `<tr><td>${ingredient.Ingredients_Name}</td><td>${ingredient.Ingredients_Quantity}</td></tr>`).join('');
    ingredientsTable += '</tbody></table>';

    var categoryTags = generateCategoryTags(recipe.categories);
    var categoryContainer = $('<div>').addClass('category-container d-flex flex-nowrap overflow-auto mb-1 mt-1').css({'width': '100%', 'height' : '5%'});
    categoryContainer.append(categoryTags);

    var mainPhotoHtml;

    // Verificar se há fotos na receita
    if (recipe.photos && recipe.photos.length > 0 && recipe.photos[0].Photo) {
        // Se houver fotos, use a primeira foto
        mainPhotoHtml = `
            <img src="data:image/jpeg;base64,${recipe.photos[0].Photo}" alt="Main Photo" class="card-img-top center-cropped" style="
                object-fit: cover;
                width: 100%;
                height: 100%;
                border-radius: 15px; 
                background: rgba(59, 59, 59, 0.95); 
                border: 3px solid rgba(180, 124, 20, 1);">
                <span style="position: absolute; bottom: 5%; left: 5%; background-color: rgba(59, 59, 59, 0.95); color: white; padding: 5px; border: 2px solid #b47c14; display: inline-block; font-family: 'Raleway', sans-serif;" class="rounded display-6 px-2">${recipe[0].Recipe_Name}</span>
        `;
    } else {
        // Se não houver fotos, use um ícone de imagem padrão
        mainPhotoHtml = `
            <i class="fas fa-image fa-5x text-secondary d-flex justify-content-center align-items-center" style="
                width: 100%;
                height: 100%;
                border-radius: 15px; 
                background: rgba(59, 59, 59, 0.95); 
                border: 3px solid rgba(180, 124, 20, 1);">
            </i>
            <span style="position: absolute; bottom: 5%; left: 5%; background-color: rgba(59, 59, 59, 0.95); color: white; padding: 5px; border: 2px solid #b47c14; display: inline-block; font-family: 'Raleway', sans-serif;" class="rounded display-6 px-2">${recipe[0].Recipe_Name}</span>
        `;
    }

console.log(categoryTags)

    var recipeDetailsHtml = `
    <div class="row">
        <div class="col-12">
            <div class="center-cropped-container" style="height: 60vh; overflow: hidden; position: relative;">
                ${mainPhotoHtml}
            </div>
        </div>
        <div class="col-12">
            ${buttonsHtml}
        </div>
        <div class="col-12 category-container d-flex flex-nowrap overflow-auto mb-1 mt-1">
            ${categoryTags}
        </div>
        <div class="col-md-12">
            <p>${recipe[0].Recipe_Description}</p>
        </div>
        <div class="col-md-6">
            ${ingredientsTable}
        </div>
        <div class="col-md-6 ">
            <h5>Instructions</h5>
            <div class="d-flex align-items-center">
                ${instructions}
            </div>
        </div>
        <div class="col-md-6 mt-1 d-flex align-items-center">
            <div class="card" style="background: rgba(255, 255, 204, 0.8);">
                <div class="card-body">
                    <h5 class="card-title" style="color: #808080;">Hints</h5>
                    ${recipe.hints.map(hint => `<p style="margin-bottom: 5px;">${hint.Hint}</p>`).join('<hr>')}
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <div class="card" style="background: rgba(176, 226, 255, 0.8);">
                <div class="card-body">
                    <h5 class="card-title" style="color: #808080;">Dicas</h5>
                    ${recipe.notes.map(note => `<p style="margin-bottom: 5px;">${note.Notes}</p>`).join('<hr>')}
                </div>
            </div>
        </div>
        <div class="col-12">
            <p>Data de Criação: ${formattedCreationDate}</p>
        </div>
    </div>
`;

document.getElementById('recipe-details').innerHTML = recipeDetailsHtml;





    // Galeria de Fotos
    var photoGalleryHtml = '<h5 class="divider line double-razor">Photos Gallery</h5><div id="photos" class="row">';
    recipe.photos.forEach(function(photo, index) {
            var imageUrl = `data:image/jpeg;base64,${photo.Photo}`;
            photoGalleryHtml += `
                <div class="col-md-4">
                    <img src="${imageUrl}" alt="Photo ${photo.Photo_ID}" class="img-fluid rounded">
                </div>`;
    });
    photoGalleryHtml += '</div>';
    document.getElementById('photo-gallery').innerHTML = photoGalleryHtml;
}

function toggleFavorite(button) {
    // Alternar a classe 'favorited' no botão
    button.classList.toggle('favorite');

    // Modificar diretamente o estilo do ícone
    var icon = button.querySelector('i');
    if (button.classList.contains('favorite')) {
        // Quando favoritado, usa o ícone sólido
        icon.classList.remove('far');
        icon.classList.add('fas');
        SetFavorites();
    } else {
        // Quando não favoritado, usa o ícone contornado
        icon.classList.remove('fas');
        icon.classList.add('far');
        UndoFavorites();
    }
}

function UndoFavorites() {
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('id');
    // Aqui você pode fazer a chamada AJAX para o método 'UndoFavorites' no servidor
    $.ajax({
        url: '../../Controllers/RecipeController.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'UndoFavorites',
            recipeId: recipeId,
            userId: userIdFromPHP
        },
        success: function(response) {
            // Lógica para manipular a resposta de sucesso, se necessário
            console.log('Recipe unfavorited successfully');
        },
        error: function(xhr, status, error) {
            // Lógica para lidar com erros na solicitação
            console.error('Erro ao desfavoritar a receita:', error);
        }
    });
}

function SetFavorites() {
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('id');
    // Aqui você pode fazer a chamada AJAX para o método 'SetFavorites' no servidor
    $.ajax({
        url: '../../Controllers/RecipeController.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'SetFavorites',
            recipeId: recipeId,
            userId: userIdFromPHP
        },
        success: function(response) {
            // Lógica para manipular a resposta de sucesso, se necessário
            console.log('Recipe favorited successfully');
        },
        error: function(xhr, status, error) {
            // Lógica para lidar com erros na solicitação
            console.error('Erro ao favoritar a receita:', error);
        }
    });
}









