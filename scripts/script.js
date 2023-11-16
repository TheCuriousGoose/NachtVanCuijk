function classroomAdder(val) {
    localStorage.setItem('selectedtem', document.getElementById('visitor').value);
    if (val === 'false') {
        let classroom = document.getElementById('classroom-section');
        classroom.style.display = 'unset';
    } else {
        let classroom = document.getElementById('classroom-section');
        classroom.style.display = 'none';
    }
}

window.onload = function () {
    if (localStorage.getItem('selectedtem')) {
        document.getElementById('visitor-' + localStorage.getItem('selectedtem')).selected = true;
        if (localStorage.getItem('selectedtem') === 'false') {
            let classroom = document.getElementById('classroom-section');
            classroom.style.display = 'unset';
        }
    }
}

function toggleMenu() {
    let x = document.getElementById("nav-links");
    document.getElementById("nav-wrapper").classList.toggle("background-purple")
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

