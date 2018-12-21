import { courseworkService } from '../_services/coursework.service';
import { router } from '../routes';

const cw = JSON.parse(localStorage.getItem('coursework'));
const initialState = cw
    ? { coursework: cw }
    : { coursework: [] };

export const courseworkdata = {
    namespaced: true,
    state: initialState,
    actions: {
        getCoursework({ dispatch, commit }, { year }) {
            commit('courseworkRequest', { year });

            courseworkService.getCoursework(year)
                .then(
                    coursework => {
                        commit('courseworkSuccess', coursework);
                    },
                    error => {
                        commit('courseworkFailure', error);
                        dispatch('alert/error', error, { root: true });
                    }
                );
        }
    },
    mutations: {
        courseworkRequest(state, coursework) {
            state.courseworks = coursework;
        },
        courseworkSuccess(state, coursework) {
            state.courseworks = coursework;
        },
        courseworkFailure(state) {
            state.courseworks = null;
        }
    }
}
