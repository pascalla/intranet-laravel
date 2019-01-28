import { timetabledata } from '../_store/timetabledata.module';

export const timetableService = {
    getTimetable
};

function getTimetable(week) {
    var formData = new FormData();
    const user = JSON.parse(localStorage.getItem('user'));
    formData.append('api', user.token);
    formData.append('week', week);

    const requestOptions = {
        method: 'POST',
        body: formData
    };

    return fetch('/api/timetable/week', requestOptions) // Call the fetch function passing the url of the API as a parameter
    .then(handleResponse)
    .then(timetable => {
        if (timetable) {
            localStorage.setItem('timetable', JSON.stringify(timetable));
        }
        return timetable;
    });
}

function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);
        if (!response.ok) {
            if (response.status === 401) {
                dispatch(logout);
            }

            const error = data.statusText;
            return Promise.reject(error);
        }

        return data;
    });
}
