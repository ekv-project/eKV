export function search(data, dataType, token){
    // Clears search result everytime key is up
    const searchResult = document.querySelector('#searchResult')
    while (searchResult.firstChild){
        searchResult.removeChild(searchResult.firstChild)
    }
    const url = '/api/search?' + new URLSearchParams('data' + '=' + data) + '&' + new URLSearchParams('dataType' + '=' + dataType);
    async function fetchData(url){
        const response = await fetch(url, {
            method: 'GET',
            mode: 'same-origin',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/json',
              'API-TOKEN': token
            }
        });
        return response.json();
    }
    function user(userType){
        function checkType(){
            if(userType == 'user'){
                let type = 'Pengguna';
                return type;
            }
            if(userType == 'student'){
                let type = 'Pelajar';
                return type;
            }
        }   
        fetchData(url)
        .then(data => {
            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                let username = element['username'];
                let fullname = element['fullname'];
                let email = element['email'];
                let searchResultChildCount = searchResult.childElementCount;
                if(searchResultChildCount < data.length){
                    const searchResult= document.getElementById('searchResult');
                    let result = document.createElement("li");
                    result.className = "list-group-item list-group-item-action searchResultItem";
                    let rowOne = document.createElement("div");
                    rowOne.append('ID ' + checkType() + ': ' + username);
                    rowOne.setAttribute("value", username);
                    let rowTwo = document.createElement("div");
                    rowTwo.append('Nama ' +  checkType() + ': ' + fullname);
                    rowTwo.setAttribute("value", fullname);
                    let rowThree = document.createElement("div");
                    rowThree.append('Emel ' + checkType() + ': ' + email);
                    rowThree.setAttribute("value", email);
                    result.appendChild(rowOne);
                    result.appendChild(rowTwo);
                    result.appendChild(rowThree);
                    searchResult.appendChild(result);
                }
            }
        });
    }
    if(dataType == 'user'){
        user('user');
    }
    if(dataType == 'student'){
        user('student');
    }
    if(dataType == 'classroom'){

    }
}  
