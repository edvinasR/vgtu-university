import {
    ERROR_COFFEE_REQEST,
    CREATED_COFFEE,
    DELETED_COFFEE,
    FETCHED_COFFEES
} from '../actions/types';

export const reducer = (state = {}, action) => {

    switch (action.type) {
        case FETCHED_COFFEES:
            return { ...state, errors: null, coffees: action.coffees , type: action.type}
        case CREATED_COFFEE:
        return { ...state, errors: null, coffees: [action.coffee].concat(state.coffees), type: action.type}
        case DELETED_COFFEE:
            return { ...state, errors: null, coffees :  state.coffees.filter((e, i) => e.id !== action.coffee.id), type: action.type}
        case ERROR_COFFEE_REQEST:
            return { ...state, errors: action.errors, type: action.type}
        default:
            return state;
    }
};
