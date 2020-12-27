require('./bootstrap');
import { search } from "./liveSearch.js";
const searchInput = document.querySelector('#searchInput');
let dataType = searchInput.getAttribute('data-type');
searchInput.addEventListener("input", function(){
    const token = document.querySelector('meta[name="api-token"]').content;
    search(searchInput.value, dataType, token);
}); 