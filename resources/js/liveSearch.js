export function searchStudent(data, searchFor, token){
    // Clears search result everytime key is up
    const searchResult = document.querySelector('#searchResult')
    while (searchResult.firstChild){
        searchResult.removeChild(searchResult.firstChild)
    }
    let url = '/api/search?' + new URLSearchParams('data' + '=' + data) + '&' + new URLSearchParams('searchFor' + '=' + searchFor);
        fetch(url, {
            method: 'GET',
            mode: 'same-origin',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/json',
              'API-TOKEN': token
        }})
        .then(response => response.json())
        .then(function(data){
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var username = element['username'];
                var fullname = element['fullname'];
                var searchResultChildCount = searchResult.childElementCount;
                if(searchResultChildCount < data.length){
                    const searchResult= document.getElementById('searchResult'); 
                    var result = document.createElement("li");
                    result.className = "list-group-item list-group-item-action searchResultItem";
                    var rowOne = document.createElement("div");
                    rowOne.append('ID Pengguna: ' + username);
                    rowOne.setAttribute("value", username);
                    var rowTwo = document.createElement("div");
                    rowTwo.append('Nama Pengguna: ' + fullname);
                    rowTwo.setAttribute("value", username);
                    result.appendChild(rowOne);
                    result.appendChild(rowTwo);
                    searchResult.appendChild(result);
                }
            }
        })
}  
