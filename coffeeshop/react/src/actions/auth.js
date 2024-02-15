import axios from 'axios';
import {ROOT_URL} from '../config'
import {
    AUTH_USER,
    UNAUTH_USER,
    AUTH_ERROR,
} from './types';



// Used to authenticate user
export const signinUser = ({ email, password }) => {
    return (dispatch) => {
        // submit email/password to the server
        axios.post(`${ROOT_URL}/login`, { email, password })
            .then(response => {
                localStorage.setItem('token', response.data.data.accessToken);
                dispatch({ type: AUTH_USER });

            }).catch((error) => {
                let errors = error.response.data.errors
                dispatch(authError(errors ? errors : { invalid : "Invalid credentials"}));
            });
    };
};

// Used to register user
export const signupUser = ({ email, name, password, confirm_password     }) => {

    return (dispatch) => {
        // submit email/password to the server
        axios.post(`${ROOT_URL}/register`, { email: email, name: name, password: password, confirm_password: confirm_password })
            .then(response => {
                localStorage.setItem('token', response.data.data.accessToken);
                dispatch({ type: AUTH_USER });
            })
            .catch(err => {
                dispatch(authError(err.response.data.errors));
            });
    };
};

export const authError = (errors) => {
    return {
        type: AUTH_ERROR,
        payload: errors
    };
};

export const signoutUser = () => {
    localStorage.removeItem('token')
    return { type: UNAUTH_USER };
};