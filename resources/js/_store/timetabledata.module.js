import { timetableService } from '../_services/timetable.service';
import { router } from '../routes';

const tt = JSON.parse(localStorage.getItem('timetable'));
const initialState = tt
    ? { timetable: tt }
    : { timetable: [] };

export const timetabledata = {
    namespaced: true,
    state: initialState,
    actions: {
        getTimetable({ dispatch, commit }, { week }) {
            commit('timetableRequest', { week });

            timetableService.getTimetable(week)
                .then(
                    timetable => {
                        commit('timetableSuccess', timetable);
                    },
                    error => {
                        commit('timetableFailure', error);
                        dispatch('alert/error', error, { root: true });
                    }
                );
        }
    },
    mutations: {
        timetableRequest(state, timetable) {
            state.timetable = timetable;
        },
        timetableSuccess(state, timetable) {
            state.timetable = timetable;
        },
        timetableFailure(state) {
            state.timetable = null;
        }
    }
}
