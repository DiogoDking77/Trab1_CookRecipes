// Inicialize a variável global ingredientsArray
var ingredientsArray = [];

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

        // Agora, ingredientsArray contém todos os ingredientes adicionados
        console.log(ingredientsArray);

        // AJAX request to send ingredient data to PHP page
    } else {
        alert('Please enter both ingredient name and quantity.');
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

    // Imprima o array no console
    console.log(window.hintArray);
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

    // Imprima o array no console
    console.log(window.noteArray);
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

// Function to add more image input fields

function CreateRecipe() {
    console.log(imageArray)
    var recipeName = document.getElementById("recipeName").value;
    var recipeDescription = document.getElementById("recipeDescription").value;
    var recipeInstructions = document.getElementById("recipeInstructions").value;

    $.ajax({
        url: '../../Controllers/RecipeController.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'addRecipe',
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
            console.log('recipeId:', response.recipeId);
            console.log('Imagens:', imageArray);
            InsertImages(response.recipeId)
        },
        error: function(xhr, status, error) {
            console.error('Erro ao criar a receita:', error);
        }
    });

    
}

function InsertImages(recipeId) {
    console.log('Função InsertImages chamada com recipeId:', recipeId);

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
                    window.location.href = '../../PHP/Pages/addCategories.php?id=' + recipeId;
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao adicionar a imagem:', error);

                // Incrementa a contagem de imagens processadas (mesmo em caso de erro)
                processedImagesCount++;

                // Verifica se todas as imagens foram processadas
                if (processedImagesCount === imageArray.length) {
                    // Todas as imagens foram processadas, redireciona para addCategorys
                    window.location.href = '../../PHP/Pages/addCategories.php?id=' + recipeId;
                }
            }
        });
    });
}








