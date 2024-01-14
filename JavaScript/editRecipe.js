document.addEventListener("DOMContentLoaded", function() {
    // Fazer uma solicitação AJAX
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('recipeId');

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

function displayRecipeDetails(recipe) {
    // Preencher os campos do formulário com os dados da resposta
    document.getElementById('recipeName').value = recipe[0].Recipe_Name;
    document.getElementById('recipeDescription').value = recipe[0].Recipe_Description;
    document.getElementById('recipeInstructions').value = recipe[0].Recipe_Instructions;

    recipe.ingredients.forEach(function (ingredient) {
        addInitialIngredients(ingredient.Ingredients_Name,ingredient.Ingredients_Quantity)
    });

    recipe.hints.forEach(function (hint) {
        addInitialHint(hint.Hint);
    });



    recipe.notes.forEach(function (note) {
        addInitialNotes(note.Notes)
    });

    // Preencher o preview de fotos, se houver
    var photoPreviewContainer = document.getElementById('photoPreview');
    photoPreviewContainer.innerHTML = ''; // Limpar os previews existentes

    recipe.photos.forEach(function (photo) {
        addInitialPreviews(photo.Photo)
    });
}

    

    var ingredientsArray = [];

    function addInitialIngredients(name,quantity) {
        // Get values from input fields
        var ingredientName = name;
        var ingredientQuantity = quantity;
        

            // Adicione o ingrediente ao array
            var ingredient = {
                name: ingredientName,
                quantity: ingredientQuantity
            };
    
            ingredientsArray.push(ingredient);
    
            // Create a new column for the ingredient
            var ingredientCol = document.createElement('div');
            ingredientCol.className = 'col-auto mb-2';
    
            // Create a span to display the ingredient
            var ingredientSpan = document.createElement('span');
            ingredientSpan.className = 'badge bg-secondary d-flex align-items-center';
            ingredientSpan.innerHTML = ingredientName + ' (' + ingredientQuantity + ')';
    
            // Create a button to remove the ingredient
            var removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-danger btn-sm ms-2';
            removeButton.innerHTML = 'X';
            removeButton.onclick = function() {
                // Remove o ingrediente do array
                var index = ingredientsArray.indexOf(ingredient);
                if (index !== -1) {
                    ingredientsArray.splice(index, 1);
                }
    
                ingredientCol.remove();
            };
    
            // Append the span and button to the column
            ingredientSpan.appendChild(removeButton);
            ingredientCol.appendChild(ingredientSpan);
    
            // Append the column to the ingredientRow
            document.getElementById('ingredientRow').appendChild(ingredientCol);
    
            // Clear input fields
            document.getElementById('ingredientName').value = '';
            document.getElementById('ingredientQuantity').value = '';
        
    }

    function addIngredient() {
        // Get values from input fields
        var ingredientName = document.getElementById('ingredientName').value;
        var ingredientQuantity = document.getElementById('ingredientQuantity').value;
    
        // Check if both fields are filled
        if (ingredientName.trim() !== '' && ingredientQuantity.trim() !== '') {
            // Adicione o ingrediente ao array
            var ingredient = {
                name: ingredientName,
                quantity: ingredientQuantity
            };
    
            ingredientsArray.push(ingredient);
    
            // Create a new column for the ingredient
            var ingredientCol = document.createElement('div');
            ingredientCol.className = 'col-auto mb-2';
    
            // Create a span to display the ingredient
            var ingredientSpan = document.createElement('span');
            ingredientSpan.className = 'badge bg-secondary d-flex align-items-center';
            ingredientSpan.innerHTML = ingredientName + ' (' + ingredientQuantity + ')';
    
            // Create a button to remove the ingredient
            var removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-danger btn-sm ms-2';
            removeButton.innerHTML = 'X';
            removeButton.onclick = function() {
                // Remove o ingrediente do array
                var index = ingredientsArray.indexOf(ingredient);
                if (index !== -1) {
                    ingredientsArray.splice(index, 1);
                }
    
                ingredientCol.remove();
            };
    
            // Append the span and button to the column
            ingredientSpan.appendChild(removeButton);
            ingredientCol.appendChild(ingredientSpan);
    
            // Append the column to the ingredientRow
            document.getElementById('ingredientRow').appendChild(ingredientCol);
    
            // Clear input fields
            document.getElementById('ingredientName').value = '';
            document.getElementById('ingredientQuantity').value = '';
    
            // AJAX request to send ingredient data to PHP page
        } else {
            alert('Please enter both ingredient name and quantity.');
        }
    }

    function addInitialHint(hintValue) {
        // Crie um novo grupo de dicas
        var newHintGroup = document.createElement('div');
        newHintGroup.className = 'hint-group mb-2';
    
        // Crie um novo input para a dica
        var newInputGroup = document.createElement('div');
        newInputGroup.className = 'input-group';
    
        var inputField = document.createElement('input');
        inputField.type = 'text';
        inputField.className = 'form-control';
        inputField.value = hintValue; // Use o valor passado como argumento
        inputField.setAttribute('readonly', true); // Tornar o campo somente leitura
        inputField.disabled = true; // Desativar o campo
    
        // Adicione o input ao grupo de input
        newInputGroup.appendChild(inputField);
        var hintArray = window.hintArray || [];
        hintArray.push(inputField.value);
        window.hintArray = hintArray;
    
        // Adicione o botão "Delete"
        var deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'btn btn-danger btn-sm'; // Adicione a classe btn-danger para tornar o botão vermelho
        deleteButton.innerHTML = 'Delete';
    
        // Adicione a função de delete para o botão
        deleteButton.addEventListener('click', function () {
            deleteHint(this);
        });
    
        // Adicione o botão ao grupo de input
        newInputGroup.appendChild(deleteButton);
    
        // Adicione o grupo de input ao grupo de dicas
        newHintGroup.appendChild(newInputGroup);
    
        // Obtenha o elemento hintContainer
        var hintContainer = document.getElementById('hintContainer');
    
        // Verifique se há algum grupo de dicas existente
        var existingHintGroup = hintContainer.querySelector('.hint-group');
    
        // Se houver um grupo de dicas existente, insira o novo grupo antes dele, caso contrário, simplesmente adicione-o ao hintContainer
        if (existingHintGroup) {
            hintContainer.insertBefore(newHintGroup, existingHintGroup);
        } else {
            hintContainer.appendChild(newHintGroup);
        }
    }
    
    
    function addHint(button) {
        // Obtenha o input e o botão dentro do grupo atual
        var hintGroup = button.closest('.hint-group');
        var inputField = hintGroup.querySelector('input');
        var addButton = hintGroup.querySelector('button');
    
        // Desative o input
        inputField.disabled = true;
    
        // Armazene o valor do input em um array do JavaScript
        var hintArray = window.hintArray || [];
        hintArray.push(inputField.value);
        window.hintArray = hintArray;
    
        // Crie um novo input-group exatamente igual ao atual
        var newHintGroup = hintGroup.cloneNode(true);
    
        // Limpe o novo input e altere o botão para "Add" com cor verde
        var newInputField = newHintGroup.querySelector('input');
        newInputField.value = '';
        newInputField.disabled = false;
    
        var newAddButton = newHintGroup.querySelector('button');
        newAddButton.innerHTML = 'Add';
        newAddButton.classList.remove('btn-danger');
        newAddButton.classList.add('btn-success');
    
        // Adicione o novo input-group após o atual
        hintGroup.parentNode.insertBefore(newHintGroup, hintGroup.nextSibling);
    
        // Substitua a função de click do novo botão
        newAddButton.setAttribute('onclick', 'addHint(this)');
    
        // Adicione a função de delete para o botão atual
        addButton.innerHTML = 'Delete';
        addButton.classList.remove('btn-success');
        addButton.classList.add('btn-danger');
        addButton.setAttribute('onclick', 'deleteHint(this)');
    }
    
    function deleteHint(button) {
        // Obtenha o input e o botão dentro do grupo atual
        var hintGroup = button.closest('.hint-group');
        var inputField = hintGroup.querySelector('input');
    
        // Remova o grupo de dicas atual
        hintGroup.parentNode.removeChild(hintGroup);
    
        // Remova o valor associado ao input desse grupo no array
        var hintArray = window.hintArray || [];
        var index = hintArray.indexOf(inputField.value);
        if (index !== -1) {
            hintArray.splice(index, 1);
        }
        window.hintArray = hintArray;
    }

    function addInitialNotes(notesValue) {
        // Crie um novo grupo de notas
        var newNotesGroup = document.createElement('div');
        newNotesGroup.className = 'note-group mb-2';
    
        // Crie um novo input para a nota
        var newInputGroup = document.createElement('div');
        newInputGroup.className = 'input-group';
    
        var inputField = document.createElement('input');
        inputField.type = 'text';
        inputField.className = 'form-control';
        inputField.value = notesValue; // Use o valor passado como argumento
        inputField.setAttribute('readonly', true); // Tornar o campo somente leitura
        inputField.disabled = true; // Desativar o campo
    
        // Adicione o input ao grupo de input
        newInputGroup.appendChild(inputField);

        var noteArray = window.noteArray || [];
        noteArray.push(inputField.value);
        window.noteArray = noteArray;
    
        // Adicione o botão "Delete"
        var deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'btn btn-danger btn-sm'; // Adicione a classe btn-danger para tornar o botão vermelho
        deleteButton.innerHTML = 'Delete';
    
        // Adicione a função de delete para o botão
        deleteButton.addEventListener('click', function () {
            deleteNote(this);
        });
    
        // Adicione o botão ao grupo de input
        newInputGroup.appendChild(deleteButton);
    
        // Adicione o grupo de input ao grupo de notas
        newNotesGroup.appendChild(newInputGroup);
    
        // Obtenha o elemento noteContainer
        var noteContainer = document.getElementById('noteContainer');
    
        // Verifique se há algum grupo de notas existente
        var existingNotesGroup = noteContainer.querySelector('.note-group');
    
        // Se houver um grupo de notas existente, insira o novo grupo antes dele, caso contrário, simplesmente adicione-o ao noteContainer
        if (existingNotesGroup) {
            noteContainer.insertBefore(newNotesGroup, existingNotesGroup);
        } else {
            noteContainer.appendChild(newNotesGroup);
        }
    }
    
    function addNote(button) {
        // Obtenha o input e o botão dentro do grupo atual
        var noteGroup = button.closest('.note-group');
        var inputField = noteGroup.querySelector('input');
        var addButton = noteGroup.querySelector('button');
    
        // Desative o input
        inputField.disabled = true;
    
        // Armazene o valor do input em um array do JavaScript
        var noteArray = window.noteArray || [];
        noteArray.push(inputField.value);
        window.noteArray = noteArray;
    
        // Crie um novo input-group exatamente igual ao atual
        var newNoteGroup = noteGroup.cloneNode(true);
    
        // Limpe o novo input e altere o botão para "Add" com cor verde
        var newInputField = newNoteGroup.querySelector('input');
        newInputField.value = '';
        newInputField.disabled = false;
    
        var newAddButton = newNoteGroup.querySelector('button');
        newAddButton.innerHTML = 'Add';
        newAddButton.classList.remove('btn-danger');
        newAddButton.classList.add('btn-success');
    
        // Adicione o novo input-group após o atual
        noteGroup.parentNode.insertBefore(newNoteGroup, noteGroup.nextSibling);
    
        // Substitua a função de click do novo botão
        newAddButton.setAttribute('onclick', 'addNote(this)');
    
        // Adicione a função de delete para o botão atual
        addButton.innerHTML = 'Delete';
        addButton.classList.remove('btn-success');
        addButton.classList.add('btn-danger');
        addButton.setAttribute('onclick', 'deleteNote(this)');
    }
    
    function deleteNote(button) {
        // Obtenha o input e o botão dentro do grupo atual
        var noteGroup = button.closest('.note-group');
        var inputField = noteGroup.querySelector('input');
    
        // Remova o grupo de dicas atual
        noteGroup.parentNode.removeChild(noteGroup);
    
        // Remova o valor associado ao input desse grupo no array
        var noteArray = window.noteArray || [];
        var index = noteArray.indexOf(inputField.value);
        if (index !== -1) {
            noteArray.splice(index, 1);
        }
        window.noteArray = noteArray;
    }

    let imageArray = [];

function handleImageUpload(event) {
    const previewArea = document.getElementById("photoPreview");

    for (let i = 0; i < event.target.files.length; i++) {
        const file = event.target.files[i];

        // Converte a imagem para base64 sem prefixo
        const reader = new FileReader();
        reader.onloadend = function () {
            const base64Data = reader.result.split(',')[1]; // Remove o prefixo
            addImageToPreview(base64Data);
        };
        reader.readAsDataURL(file);
    }
}

function addInitialPreviews(photoData) {
    // Add photo preview
    const previewElement = document.createElement("div");
    previewElement.classList.add("col-md-3", "mb-3", "position-relative");
    previewElement.innerHTML = `
        <img src="data:image/png;base64,${photoData}" class="img-fluid" alt="Preview">
        <button type="button" class="btn btn-danger position-absolute top-0 start-0" onclick="removeImage(this)">Remove</button>
    `;

    // Adiciona o preview à área de visualização
    const previewArea = document.getElementById("photoPreview");
    previewArea.appendChild(previewElement);

    // Armazena os dados base64 no array
    imageArray.push(photoData);
}

function addImageToPreview(base64Data) {
    // Add image preview
    const previewElement = document.createElement("div");
    previewElement.classList.add("col-md-3", "mb-3", "position-relative");
    previewElement.innerHTML = `
        <img src="data:image/png;base64,${base64Data}" class="img-fluid" alt="Preview">
        <button type="button" class="btn btn-danger position-absolute top-0 start-0" onclick="removeImage(this)">Remove</button>
    `;

    // Adiciona o preview à área de visualização
    const previewArea = document.getElementById("photoPreview");
    previewArea.appendChild(previewElement);

    // Armazena os dados base64 no array
    imageArray.push(base64Data);
}



function removeImage(element) {
    // Remove preview
    const index = Array.from(element.parentNode.parentNode.children).indexOf(element.parentNode);
    element.parentNode.remove();

    // Remove base64 from array
    imageArray.splice(index, 1);
}

function editRecipe() {
    
    var recipeName = document.getElementById("recipeName").value;
    var recipeDescription = document.getElementById("recipeDescription").value;
    var recipeInstructions = document.getElementById("recipeInstructions").value;
    
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('recipeId');

    $.ajax({
        url: '../../Controllers/RecipeController.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'editRecipe',
            recipeId : recipeId,
            recipeName: recipeName,
            description: recipeDescription,
            instructions: recipeInstructions,
            ingredientsArray: ingredientsArray,
            hintsArray: hintArray,
            notesArray: noteArray,
            userId: userIdFromPHP,
        },
        success: function(response) {
            console.log('Ação enviada do lado do cliente:', 'addPhoto');
            console.log('Imagens:', imageArray);
            DeleteImages()
        },
        error: function(xhr, status, error) {
            console.error('Erro ao criar a receita:', error);
        }
    });

    
}

function DeleteImages() {
    console.log('Função InsertImages chamada com recipeId:', recipeId);
    console.log(imageArray);
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('recipeId');

    // Inicializa a contagem de imagens processadas
    var processedImagesCount = 0;

    // Itera sobre o array de imagens e envia cada uma para o servidor
    
        $.ajax({
            url: '../../Controllers/RecipeController.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'deletePhotos',
                recipeId: recipeId,
            },
            success: function(response) {
                InsertImages()
            },
            error: function(xhr, status, error) {
                console.error('Erro ao adicionar a imagem:', error);
            }
        });
}

function InsertImages() {
    console.log('Função InsertImages chamada com recipeId:', recipeId);
    console.log(imageArray);
    var urlParams = new URLSearchParams(window.location.search);
    var recipeId = urlParams.get('recipeId');

    // Inicializa a contagem de imagens processadas
    var processedImagesCount = 0;

    // Itera sobre o array de imagens e envia cada uma para o servidor
    imageArray.forEach(function(base64Data, index) {
        $.ajax({
            url: '../../Controllers/RecipeController.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'addPhotos',
                recipeId: recipeId,
                imageIndex: index, // Para identificar a ordem da imagem
                imageData: base64Data
            },
            success: function(response) {
                console.log('Imagem adicionada com sucesso:', response);

                // Incrementa a contagem de imagens processadas
                processedImagesCount++;

                // Verifica se todas as imagens foram processadas
                if (processedImagesCount === imageArray.length) {
                    // Todas as imagens foram processadas, redireciona para addCategorys
                    window.location.href = '../../PHP/Pages/RecipePage.php?id=' + recipeId;
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao adicionar a imagem:', error);

                // Incrementa a contagem de imagens processadas (mesmo em caso de erro)
                processedImagesCount++;

                // Verifica se todas as imagens foram processadas
                if (processedImagesCount === imageArray.length) {
                    // Todas as imagens foram processadas, redireciona para addCategorys
                    window.location.href = '../../PHP/Pages/RecipePage.php?id=' + recipeId;
                }
            }
        });
    });
}

