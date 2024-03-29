import {
    AUTH_USER,
    UNAUTH_USER,
    AUTH_ERROR
} from '../actions/types';

export const reducer = (state = {}, action) => {

    switch (action.type) {
        case AUTH_USER:
            return { ...state, errors: null, authenticated: true }
        case UNAUTH_USER:
            return { ...state, authenticated: false }
        case AUTH_ERROR:
            return { ...state, errors: action.payload }
        default:
            return state;
    }
};
