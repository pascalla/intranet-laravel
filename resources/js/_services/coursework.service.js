import { courseworkdata } from '../_store/courseworkdata.module';

export const courseworkService = {
    getCoursework
};

function getCoursework(year) {
    var formData = new FormData();
    const user = JSON.parse(localStorage.getItem('user'));
    formData.append('api', user.token);
    formData.append('year', year);

    const requestOptions = {
        method: 'POST',
        body: formData
    };

    return fetch('/api/coursework/year', requestOptions) // Call the fetch function passing the url of the API as a parameter
    .then(handleResponse)
    .then(coursework => {
        console.log(coursework);
        if (coursework.coursework) {
            localStorage.setItem('coursework', JSON.stringify(coursework.coursework));
        }
        return coursework.coursework;
    });
}

function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);
        if (!response.ok) {
            if (response.status === 401) {
                //dispatch(logout);
            }

            const error = data.statusText;
            return Promise.reject(error);
        }

        return data;
    });
}
