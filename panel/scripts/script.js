function executeDatabaseChange(personId, value, updateColumn) {
    const requestData = {
        personId: personId,
        value: value,
        updateColumn: updateColumn
    };

    $.ajax({
        url: 'signup-processor.php',
        type: 'POST', // or 'GET' depending on your needs
        dataType: 'html',
        data: requestData, // data to be sent
        success: function(response) {
            // Handle the response from the PHP script
            // console.log(response);
        },
        error: function(xhr, status, error) {
            // Handle errors
            console.error(error);
        }
    });
}

function updatePresents(checkbox) {
    if (checkbox.checked) {
        executeDatabaseChange(checkbox.name, 1, 'Present');
    } else {
        executeDatabaseChange(checkbox.name, 0, 'Present');

    }

}

function updateSnackcar(checkbox) {
    if (checkbox.checked) {
        executeDatabaseChange(checkbox.name, 1, 'PaidSnackCar');
    } else {
        executeDatabaseChange(checkbox.name, 0, 'PaidSnackCar');
    }
}