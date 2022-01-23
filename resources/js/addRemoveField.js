if(document.querySelector('#addField')){
    let addFieldButton = document.querySelector('#addField');
    let fieldType = addFieldButton.getAttribute('data-field-type');
    let increment = 1;
    addFieldButton.addEventListener('click', ()=> {
        if(fieldType == 'courseGrade'){
            let parentNode = document.querySelector('#courseGrade');
            let childNode = document.createElement('div');
            let child = '<div class="col border border-dark mt-1 d-flex flex-column justify-content-center align-items-center"><div class="form-floating m-2"><input type="text" class="form-control" id="coursesCode" name="coursesCode[]" placeholder="coursesCode" required><label for="coursesCode">Kod Kursus</label></div><div class="form-floating m-2"><input type="text" class="form-control" id="gradePointer" name="gradePointer[]" placeholder="gradePointer" required><label for="gradePointer">Nilai Gred</label></div><button type="button" id="removeField" data-remove-field-type="courseGrade" data-remove-field-id="courseGrade" class="btn btn-danger m-1 fs-hvr-shrink">Keluarkan</button></div>';
            childNode.innerHTML = child;
            parentNode.appendChild(childNode);
            increment++;
        }
    });
}
// Remove Button
    // Event Delegation
    document.querySelector("#courseGrade").addEventListener('click', function(e) {
        if(e.target.id == 'removeField') {
            e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
        }
    });
