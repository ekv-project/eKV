require('./bootstrap');
import { searchStudent } from "./liveSearch.js";
const studentInput = document.querySelector('#searchInput');
studentInput.addEventListener("input", function(){
    const token = document.querySelector('meta[name="api-token"]').content;
    searchStudent(studentInput.value, 'student', token);
}); 