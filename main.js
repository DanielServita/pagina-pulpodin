const langButtons = document.querySelectorAll("[data-language]");
const textToChange = document.querySelectorAll("[data-section]");

langButtons.forEach(button => { // Corrección aquí
    button.addEventListener("click", () => {
        fetch(`../languajes/${button.dataset.language}.json`)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                // Aquí puedes agregar la lógica para actualizar el contenido del sitio web con los datos del JSON
            })
    });
});
