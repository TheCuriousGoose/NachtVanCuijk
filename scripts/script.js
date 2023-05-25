function classroomAdder(val){
    localStorage.setItem('selectedtem', document.getElementById('visitor').value);
    if(val === 'no'){
        let classroom = document.getElementById('classroom-section');
        classroom.style.display = 'unset';
    }else {
        let classroom = document.getElementById('classroom-section');
        classroom.style.display = 'none';
    }
}

window.onload = function() {
    if (localStorage.getItem('selectedtem')) {
        document.getElementById('visitor-' + localStorage.getItem('selectedtem')).selected = true;
        if(localStorage.getItem('selectedtem') === 'no'){
            let classroom = document.getElementById('classroom-section');
            classroom.style.display = 'unset';
        }
    }
}
