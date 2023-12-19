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
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'createRecipe.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        // Convert the array to JSON and send it in the request
        var ingredientsArray = [{ name: ingredientName, quantity: ingredientQuantity }];
        xhr.send('ingredients=' + JSON.stringify(ingredientsArray));
    } else {
        alert('Please enter both ingredient name and quantity.');
    }
}




function toggleButtons(button) {
    var inputGroup = $(button).closest('.input-group');
    var inputField = inputGroup.find('input');

    // Check if the input is not empty
    if (!inputField.val().trim()) {
        alert('Please enter a value before adding.');
        return;
    }

    // Disable input field
    inputField.prop('disabled', true);

    // Change button to "Delete"
    $(button).removeClass('btn-success').addClass('btn-danger').text('Delete');
    $(button).attr('onclick', 'removeItem(this)');

    // Add a new item field with "Add" button
    var newItemFieldWithAdd = '<div class="' + (inputField.attr('name') === 'hints[]' ? 'hint-group' : 'note-group') + ' mb-2"><div class="input-group">';
    newItemFieldWithAdd += '<input type="text" class="form-control" name="' + inputField.attr('name') + '" placeholder="' + inputField.attr('placeholder') + '">';
    newItemFieldWithAdd += '<button type="button" class="btn btn-success btn-sm" onclick="toggleButtons(this)">Add</button>';
    newItemFieldWithAdd += '</div></div>';

    // Append the new item field with "Add" button
    if (inputField.attr('name') === 'hints[]') {
        $('#hintContainer').append(newItemFieldWithAdd);
    } else if (inputField.attr('name') === 'notes[]') {
        $('#noteContainer').append(newItemFieldWithAdd);
    }
}

function removeItem(button) {
    // Remove the parent div of the clicked button (which contains both input and buttons)
    $(button).closest('.note-group, .hint-group').remove();
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


