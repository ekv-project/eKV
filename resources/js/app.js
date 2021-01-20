// Don't forget to execute npm run prod for production instead of
// npm run dev/watch to reduce CSS and JS size

require('../../node_modules/@popperjs/core/dist/esm/popper');
require('../bootstrap/js/index.esm');
import { search } from "./liveSearch.js";

if(document.querySelector('#searchInput') != null){
    let searchInput = document.querySelector('#searchInput');
    let dataType = searchInput.getAttribute('data-type');
    searchInput.addEventListener("input", () => {
        let token = document.querySelector('meta[name="api-token"]').content;
        search(searchInput.value, dataType, token);
    }); 
}
if(document.querySelector('.hamburger-menu') != null){
    let hamburgerMenu = document.querySelector('.hamburger-menu');
    let hamburgerMenuList = document.querySelector('.hamburger-menu-list');
    hamburgerMenu.addEventListener('click', () => {
        hamburgerMenuList.classList.toggle('visible');
        hamburgerMenuList.classList.toggle('invisible');
    });
}

