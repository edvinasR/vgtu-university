function createConfig(contentType){
    // return authorization header with jwt token
    let token = localStorage.getItem('token');
    if (token) {
        return { headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': contentType,
            'Accept' : 'application/json'
            }
        };
    } else {
        return {};
    }
}

export function authHeader() {
    
    return createConfig('application/json');
}
export function authHeaderForImageUpload() {

    return createConfig('multipart/form-data');

}


