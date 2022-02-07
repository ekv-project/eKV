const courseList = document.querySelector('#course-list');
var i = 0;
if(document.querySelector('#course-set-course-add')){
    let addFieldButton = document.querySelector('#course-set-course-add');
    addFieldButton.addEventListener('click', ()=> {
        // Max courses = 12
        if(i < 12 && courseList.childElementCount < 12){
            let childNode = document.createElement('div');
            childNode.classList = 'col-6 col-lg-4';
            let childNodeHTML = `
                    <div class="form-floating mb-3">
                        <input type="text" name="course_code[]" id="course_code[]" class="form-control" placeholder="course_code[]" value="">
                        <label for="course_code[]" class="form-label">Kod Kursus</label>
                    </div>
            `;
            childNode.innerHTML = childNodeHTML;
            courseList.appendChild(childNode);
            i++;
        }
    });
}

if(document.querySelector('#course-set-course-remove')){
    let removeFieldButton = document.querySelector('#course-set-course-remove');
    removeFieldButton.addEventListener('click', ()=> {
        if(courseList.childElementCount > 0){
            courseList.lastElementChild.remove();
            i--;
        }
    });
}
