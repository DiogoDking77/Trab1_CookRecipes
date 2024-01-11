function addIngredient() {
    // Get values from input fields
    var ingredientName = document.getElementById('ingredientName').value;
    var ingredientQuantity = document.getElementById('ingredientQuantity').value;

    // Check if both fields are filled
    if (ingredientName.trim() !== '' && ingredientQuantity.trim() !== '') {
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




function handleFileSelect(event) {
    var files = event.target.files;

    // Display preview for each selected file
    for (var i = 0; i < files.length; i++) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var previewContainer = document.createElement('div');
            previewContainer.className = 'col-md-3 mb-3';

            var preview = document.createElement('img');
            preview.className = 'img-thumbnail';
            preview.src = e.target.result;

            previewContainer.appendChild(preview);
            document.getElementById('photoPreview').appendChild(previewContainer);
        };

        reader.readAsDataURL(files[i]);
    }
}

// Function to add more image input fields
function addImageInput() {
    var input = document.createElement('input');
    input.type = 'file';
    input.className = 'form-control visually-hidden'; // Added a class to hide the input visually
    input.name = 'recipePhotos[]';
    input.accept = 'image/*';
    input.multiple = true;

    // Add an event listener to the new input field
    input.addEventListener('change', handleFileSelect);

    // Create a label to trigger the file input
    var label = document.createElement('label');
    label.className = 'btn btn-secondary'; // Style the label like a button
    label.innerHTML = 'Add More Images';
    label.appendChild(input);

    // Append the label to the photoPreview div
    document.getElementById('photoPreview').insertAdjacentElement('beforeend', label);

    // Trigger click event to open the file dialog
    input.click();
}

function CreateRecipe() {
    var RecipeName = document.getElementById('recipeName').value;
    var RecipeDescription = document.getElementById('recipeDescription').value;
    var RecipeInstructions = document.getElementById('recipeInstructions').value;
    var ingredientArray = [];
    var hintArray = window.hintArray || [];
    var noteArray = window.noteArray || [];

    // Coletar os arrays de ingredientes
    var ingredientCols = document.querySelectorAll('#ingredientRow .col-auto');
    ingredientCols.forEach(function (col) {
        var ingredientName = col.querySelector('span').textContent.split(' (')[0];
        var ingredientQuantity = col.querySelector('span').textContent.split('(')[1].split(')')[0];
        ingredientArray.push({ name: ingredientName, quantity: ingredientQuantity });
    });

    var formData = new FormData();
    formData.append('recipeName', RecipeName);
    formData.append('recipeDescription', RecipeDescription);
    formData.append('recipeInstructions', RecipeInstructions);
    formData.append('ingredients', JSON.stringify(ingredientArray));
    formData.append('hints', JSON.stringify(hintArray));
    formData.append('notes', JSON.stringify(noteArray));

    var xhr = new XMLHttpRequest();

    // Configurar a requisição
    xhr.open('POST', '../../Controllers/RecipeController.php', true);
    
    // Definir a função de retorno de chamada
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                var responseData = JSON.parse(xhr.responseText);
                
                if (responseData.error) {
                    console.error('Erro ao criar a receita: ' + responseData.error);
                } else if (responseData.redirectUrl) {
                    console.log('ID recebido. Redirecionando para ' + responseData.redirectUrl);
                    window.location.href = responseData.redirectUrl;
                } else {
                    console.log('Resposta inesperada do servidor:', responseData);
                }
            } catch (error) {
                console.error('Erro ao analisar a resposta JSON:', error);
            }
        } else {
            // Houve um erro na requisição
            console.error('Erro na requisição: ' + xhr.statusText);
        }
    };
    
    // Enviar a requisição
    var jsonData = JSON.stringify({
        recipeName: RecipeName,
        recipeDescription: RecipeDescription,
        recipeInstructions: RecipeInstructions,
        ingredients: ingredientArray,
        hints: hintArray,
        notes: noteArray
    });
    
    // Enviar a requisição com os dados JSON
    xhr.send(jsonData);
    
}
