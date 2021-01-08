// Don't forget to execute npm run prod for production instead of
// npm run dev/watch to reduce CSS and JS size

require('./bootstrap');
import { search } from "./liveSearch.js";

if(document.querySelector('#searchInput') != null){
    let searchInput = document.querySelector('#searchInput');
    let dataType = searchInput.getAttribute('data-type');
    searchInput.addEventListener("input", function(){
        let token = document.querySelector('meta[name="api-token"]').content;
        search(searchInput.value, dataType, token);
    }); 
}