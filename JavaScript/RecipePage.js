


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

    var buttonsHtml = '<div class="d-flex justify-content-end">';

    if (recipe.isFavorited) {
        // Se for true, definir a classe do ícone como "fas fa-heart"
        buttonsHtml += '<button class="btn btn-outline-light border-0 opacity-100 text-danger favorite" onclick="toggleFavorite(this)"><i class="fas fa-heart"></i></button>';
    } else {
        // Se for false, definir a classe do ícone como "far fa-heart"
        buttonsHtml += '<button class="btn btn-outline-light border-0 opacity-100 text-danger" onclick="toggleFavorite(this)"><i class="far fa-heart"></i></button>';
    }

    buttonsHtml += '<button class="btn btn-outline-light border-0" onclick="ShareRecipe()"><i class="fas fa-share text-primary"></i></button>';

    if (userIdFromPHP && userIdFromPHP === recipe[0].User_ID) {
        var urlParams = new URLSearchParams(window.location.search);
        var recipeId = urlParams.get('id');

        buttonsHtml += '<div class="dropdown ml-2">';
        buttonsHtml += '<button class="btn border-0 text-black" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="toggleEditDeleteButtons()" style="background-color: rgba(255, 255, 255, 0.5);">';
        buttonsHtml += '<i class="fas fa-cogs text-black"></i>';
        buttonsHtml += '</button>';
        buttonsHtml += '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" id="editDeleteButtons" style="left: auto; right: 0;">';
        buttonsHtml += '<button class="btn btn-outline-success dropdown-item" onclick="editarReceita(' + recipeId + ',' + userIdFromPHP + ')">Edit</button>';
        buttonsHtml += '<button class="btn btn-outline-danger dropdown-item" onclick="deleteRecipe(' + recipeId + ')" style="color: red;">Delete</button>';
        buttonsHtml += '</div>';
        buttonsHtml += '</div>';
    }

    buttonsHtml += '</div>';


    
    var instructions = recipe[0].Recipe_Instructions.replace(/\n/g, '<br>'); // Substitui \n por <br>

    // Formatação da Data de Criação
    var creationDate = new Date(recipe[0].Creation_Date);
    var formattedCreationDate = `${('0' + (creationDate.getMonth() + 1)).slice(-2)}/
                                 ${('0' + creationDate.getDate()).slice(-2)}/
                                 ${creationDate.getFullYear()}`;

    var ingredientsTable = '<h5>Ingredientes</h5><table class="table" ><thead><tr><th>Name</th><th>Quantity</th></tr></thead><tbody>';
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
                    <h5 class="card-title" style="color: #808080;">Notes</h5>
                    ${recipe.notes.map(note => `<p style="margin-bottom: 5px;">${note.Notes}</p>`).join('<hr>')}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <p>Creation Date: ${formattedCreationDate} || Creator: ${recipe.creator.User_Email}</p>
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

var users;
var selectedUsersIndex = [];
var selectedUsers = [];

function ShareRecipe() {
    $.ajax({
        url: '../../Controllers/RecipeController.php?action=GetUsers',
        method: 'GET',
        dataType: 'json',
        data: {
            userId: userIdFromPHP
        },
        success: function(response) {
            users = response.users;
            displayUsersList(users);
        },
        error: function(xhr, status, error) {
            console.error('Erro na solicitação de receitas:', error);
        }
    });

    var sharePopup = document.createElement('div');
    sharePopup.classList.add('share-popup', 'position-fixed', 'top-50', 'start-50', 'translate-middle', 'p-4', 'shadow-lg');
    sharePopup.innerHTML = `
        <div class="d-flex justify-content-between p-2 rounded text-white">
            <div>
                <p>Share with Friends this Recipe</p>
            </div>
            <div>
                <button type="button" class="btn-close btn-close-white " aria-label="Fechar" onclick="closeSharePopup()"></button>
            </div>
        </div>
        <div id="userSearchResults"></div>
        <button class="btn btn-primary" onclick="shareRecipe()">Share</button>
    `;

    document.body.appendChild(sharePopup);
    document.body.style.overflow = 'hidden';
    updateSearchResults();
}

function closeSharePopup() {
    var sharePopup = document.querySelector('.share-popup');
    if (sharePopup) {
        sharePopup.remove();
        document.body.style.overflow = '';
    }
}

function displayUsersList(users) {
    var sharePopup = document.querySelector('.share-popup');

    var searchBarHtml = `
        <div class="d-flex justify-content-between p-2 rounded text-white">
            <div>
                <p>Share with Friends this Recipe</p>
            </div>
            <div>
                <button type="button" class="btn-close btn-close-white " aria-label="Fechar" onclick="closeSharePopup()"></button>
            </div>
        </div>
        <input class="form-control me-2 mb-3" type="search" id="userSearchInput" placeholder="Search Users" aria-label="Search" oninput="updateSearchResults()">
        <div id="userSearchResults"></div>
        <button class="btn btn-primary" onclick="shareRecipe()">Share</button>
    `;

    sharePopup.innerHTML = searchBarHtml;

    updateSearchResults();
}


function updateSearchResults() {
    var resultsContainer = document.getElementById('userSearchResults');
    resultsContainer.innerHTML = '';

    // Adicionar usuários selecionados
    if (selectedUsers.length > 0) {
        resultsContainer.innerHTML += '<hr><p class="text-white">Selected Users:</p>';
        for (var i = 0; i < selectedUsers.length; i++) {
            var selectedUserItem = document.createElement('div');
            selectedUserItem.classList.add('user-item', 'text-black', 'selected-user', 'mb-2', 'd-flex', 'justify-content-between', 'align-items-center');
            selectedUserItem.innerHTML = `
                <span>${selectedUsers[i].User_Name} - ${selectedUsers[i].User_Email}</span>
                <button class="btn btn-danger btn-sm ms-auto" onclick="removeSelectedUser(${i})">Remove</button>`;
            resultsContainer.appendChild(selectedUserItem);
        }
    }

    // Filtrar usuários com base na pesquisa
    var searchInput = document.getElementById('userSearchInput').value.trim().toLowerCase();
    var filteredUsers = users.filter(function(user) {
        return user.User_Name.toLowerCase().includes(searchInput) || user.User_Email.toLowerCase().includes(searchInput);
    });

    // Adicionar usuários filtrados
    if (filteredUsers.length > 0) {
        for (var i = 0; i < Math.min(5, filteredUsers.length); i++) {
            var userItem = document.createElement('div');
            userItem.classList.add('user-item', 'text-white');

            // Usar um closure para capturar o valor do i no momento do loop
            (function(index) {
                userItem.addEventListener('click', function() {
                    selectUser(index);
                });
            })(i);

            userItem.innerHTML = `
                <span>${filteredUsers[i].User_Name} - ${filteredUsers[i].User_Email}</span>
                ${i < Math.min(4, filteredUsers.length - 1) ? '<hr>' : ''}`;
            resultsContainer.appendChild(userItem);
        }
    } else if (selectedUsers.length === 0) {
        resultsContainer.innerHTML += '<p>No results found.</p>';
    }
}


function selectUser(index) {
    // Obter o usuário correspondente ao índice
    var user = users[index];

    // Verificar se o usuário já está na lista de selecionados
    if (!selectedUsers.includes(user)) {
        selectedUsers.push(user);
        selectedUsersIndex.push(index);
    }

    // Atualizar os resultados
    updateSearchResults();
}

function shareRecipe() {
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('id');

    // Adicionar log para verificar selectedUsers
    console.log('selectedUsers:', selectedUsers);

    // Obtenha os IDs dos usuários selecionados
    


    // Realize a iteração sobre os IDs dos usuários selecionados
    selectedUsers.forEach(function(friendId) {
        console.log(userIdFromPHP,friendId.User_ID,recipeId);
        $.ajax({
            url: '../../Controllers/RecipeController.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'ShareRecipe',
                userId: userIdFromPHP,
                friendId: friendId.User_ID,
                recipeId: recipeId
            },
            success: function(response) {
                // Lógica de sucesso (se necessário)
                console.log('Compartilhado com sucesso com o usuário de ID ' + friendId);
            },
            error: function(xhr, status, error) {
                console.error('Erro ao compartilhar com o usuário de ID ' + friendId + ':', error);
            }
        });
    });

    // Feche o pop-up após compartilhar
    closeSharePopup();
}

function deleteRecipe(recipeId) {
    // Criar o elemento do popup de senha
    var passwordPopup = document.createElement('div');
    passwordPopup.classList.add('share-popup', 'position-fixed', 'top-50', 'start-50', 'translate-middle', 'p-4', 'shadow-lg');
    passwordPopup.innerHTML = `
        <div class="d-flex justify-content-between p-2 rounded text-white">
            <div>
                <p>Enter your password to delete this recipe</p>
            </div>
            <div>
                <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="closePasswordPopup()"></button>
            </div>
        </div>
        <input type="password" id="passwordInput" class="form-control mb-3" placeholder="Your Password">
        <div id="errorContainer" class="alert alert-danger mb-2 d-none" role="alert"></div>
        <button class="btn btn-danger" onclick="confirmDeleteRecipe(${recipeId})">Delete Recipe</button>
    `;

    // Adicionar o elemento ao corpo do documento
    document.body.appendChild(passwordPopup);
}

function confirmDeleteRecipe(recipeId) {
    // Obter a senha do input
    var userPassword = document.getElementById('passwordInput').value;

    // Verificar se a senha é válida e prosseguir com a exclusão
    if (userPassword.trim() !== "") {
        $.ajax({
            url: '../../Controllers/RecipeController.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'DeleteRecipe',
                recipeId: recipeId,
                userPassword: userPassword,
                userId: userIdFromPHP
            },
            success: function (response) {
                if (response.success) {
                    // Handle success
                    if (response.redirect) {
                        // Redirecionar para a página especificada
                        window.location.href = response.redirect;
                    } else {
                        console.log('Recipe deleted successfully');
                    }
                    // Fechar o popup apenas em caso de sucesso
                    closePasswordPopup();
                } else {
                    // Exibir mensagem de erro
                    displayErrorMessage(response.error || 'Something went wrong, please check your password');
                }
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error('Error deleting recipe:', error);
                // Exibir mensagem de erro
                displayErrorMessage('Something went wrong, please try again');
            }
        });
    }
}

function closePasswordPopup() {
    // Remover o popup de senha
    var passwordPopup = document.querySelector('.share-popup');
    if (passwordPopup) {
        passwordPopup.parentNode.removeChild(passwordPopup);
    }
}

function displayErrorMessage(message) {
    // Exibir mensagem de erro dentro da área de erro
    var errorContainer = document.getElementById('errorContainer');
    if (errorContainer) {
        errorContainer.textContent = message;
        // Mostrar o card de erro
        errorContainer.classList.remove('d-none');
    }
}

function toggleEditDeleteButtons() {
    var editDeleteButtons = document.getElementById('editDeleteButtons');
    if (editDeleteButtons) {
        editDeleteButtons.classList.toggle('show');
    }
}