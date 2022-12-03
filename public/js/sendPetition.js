let book_id;
window.onload = () => {
    let date_input = document.getElementById("custom-input-date");
    const date_table = document.getElementsByClassName("date_table");


    date_input.addEventListener('mouseover', (e) => {
        for (let i = 0; i < date_table.length; i++) {
            const element = date_table[i];
            const element_parent = element.parentNode;
            element_parent.style.display = ""
            date_input.value = ""
        }
    })

    date_input.addEventListener('change', (e) => {
        for (let i = 0; i < date_table.length; i++) {
            const element = date_table[i];
            const element_parent = element.parentNode;
            //Hacemos esto para crear la fecha con el formato que llega desde el input para hacer la validación
            const  date_month = element.textContent.slice(0, 2);
            const date_day = element.textContent.slice(3, 5);
            const date_year = element.textContent.slice(6, 10);
            const new_date = date_year + "-" +  date_month + "-" + date_day;
            //-------------------------------------------
            //Traemos el elemento contenedor de cada uno y hace ocultar y traer los filtros
            //Validamos las fechas y si es diferente vamos a ocultar los elemento que no coinciden
            if (date_input.value != new_date) {
                element_parent.style.display = "none"
            }
        }
    });
}
function showModal() {
    $('#exampleModal').modal('show')
}
function closeModal() {
    $('#exampleModal').modal('hide')
}

async function registerBook() {
    let title = document.getElementById("title").value;
    let ISBN = document.getElementById("ISBN").value;
    let year_publication = document.getElementById("year_publication").value;
    let user_id = document.getElementById("user").value;
    try {
        let result_register = await fetch(url_register, {
            method: 'POST',
            body: JSON.stringify({
                title: title,
                ISBN: ISBN,
                year_publication: year_publication,
                user_id: user_id,
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        const result = await result_register.json()
        if (result.errors) {
            throw result.errors
        } else {
            jQuery('.alert-danger').html('');
            jQuery('.alert-info').html('');

            jQuery('.alert-danger').hide();
            jQuery('.alert-info').show();
            jQuery('.alert-info').append('<li>Se creo correctamente</li>');
            $('#exampleModal').modal('hide');
            setInterval(location.reload(true), 10000);
        }
    } catch (error) {
        jQuery('.alert-danger').html('');

        jQuery.each(error, function (key, value) {
            jQuery('.alert-danger').show();
            jQuery('.alert-danger').append('<li>' + value + '</li>');
        });
        setInterval(location.reload(true), 10000);
    }
}

function editConfig(book) {
    $('#exampleModal').modal('show')
    let title = document.getElementById("title");
    let ISBN = document.getElementById("ISBN");
    let year_publication = document.getElementById("year_publication");
    let user_id = document.getElementById("user"); 
    title.value = book.title;
    ISBN.value = book.ISBN;
    year_publication.value = book.year_publication;
    user_id.value = book.user_id;
    book_id = book.id;
    jQuery('#edit').show();
    jQuery('#register').hide();
}

async function editBook() {
    let title = document.getElementById("title").value;
    let ISBN = document.getElementById("ISBN").value;
    let year_publication = document.getElementById("year_publication").value;
    let user_id = document.getElementById("user").value;
    try {
        let result_edit = await fetch(url_edit, {
            method: 'POST',
            body: JSON.stringify({
                title: title,
                ISBN: ISBN,
                year_publication: year_publication,
                user_id: user_id,
                book_id: book_id
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        const result = await result_edit.json()
        if (result.errors) {
            throw result.errors
        } else {
            jQuery('.alert-danger').html('');
            jQuery('.alert-info').html('');

            jQuery('.alert-danger').hide();
            jQuery('.alert-info').show();
            jQuery('.alert-info').append('<li>Se edito correctamente</li>');
            $('#exampleModal').modal('hide');

            setInterval(location.reload(true), 10000);
        }
    } catch (error) {
        jQuery('.alert-danger').html('');

        jQuery.each(error, function (key, value) {
            jQuery('.alert-danger').show();
            jQuery('.alert-danger').append('<li>' + value + '</li>');
        });
        setInterval(location.reload(true), 10000);
    }
}