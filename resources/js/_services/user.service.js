export const userService = {
    login,
    logout
};


function login(username, password) {
    var formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    const requestOptions = {
        method: 'POST',
        body: formData
    };

    return fetch('/api/login', requestOptions) // Call the fetch function passing the url of the API as a parameter
    .then(handleResponse)
    .then(user => {
        // login successful if there's a jwt token in the response
        if (user.user.token) {
            // store user details and jwt token in local storage to keep user logged in between page refreshes
            localStorage.setItem('user', JSON.stringify(user.user));
        }

        return user;
    });
}

function logout() {
    // remove user from local storage to log user out
    localStorage.removeItem('user');
}

function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);
        if (!response.ok) {
            if (response.status === 401) {
                // auto logout if 401 response returned from api
                logout();
                //location.reload(true);
            }

            const error = data.statusText;
            return Promise.reject(error);
        }

        return data;
    });
}
