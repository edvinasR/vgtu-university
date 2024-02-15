import axios from 'axios';
import {ROOT_URL, PER_PAGE} from '../config'
import { authHeader, authHeaderForImageUpload } from '../helpers/auth_header';
import {signoutUser} from './auth';
import {
    DELETED_COFFEE,
    FETCHED_COFFEES,
    CREATED_COFFEE,
    ERROR_COFFEE_REQEST,

} from './types';

// Deletes coffee from database and updates UI leter
export const deleteCoffee = (coffee) => {

    return (dispatch) => {
        
        axios.delete(`${ROOT_URL}/coffees/${coffee.id}`, authHeader())
            .then(response => {
                dispatch(coffeeDeleted(response.data.data));
            })
            .catch(err => {
                // Means coffee already deleted so dispatching deletion
                if(err.response.status == 404){
                    dispatch(coffeeDeleted(coffee));
                } else if(err.response.status == 401) {
                    dispatch(signoutUser());
                }
                // TODO handle other exceptions
        });
    };
};
// Returns all users coffees in shop
export const fetchAllCoffees = () => {

    return (dispatch) => {
        axios.get(`${ROOT_URL}/coffees?per_page=${PER_PAGE}`,authHeader())
            .then(response => {
                dispatch({
                    type: FETCHED_COFFEES,
                    coffees: response.data.data,
                });
          
            })
            .catch(err => {
                // Means token is not valid anymore so logging out currently authenticated user
                if(err.response.status == 401){
                    dispatch(signoutUser());
                }
                // TODO handle other exceptions
            });
    };
}
// Creates new coffee and saves it in database
export const createCoffee = ({title, price, image}) => {

    return (dispatch) => {
        let formData = new FormData();
        formData.append('title', title)
        formData.append('price', price)
        formData.append('image', image)
        // submit cofeeData to the server
        axios.post(`${ROOT_URL}/coffees`, formData ,authHeaderForImageUpload())
            .then(response => {
                dispatch(coffeeCreated(response.data.data));
            })
            .catch(err => {
                if(err.response.status == 400 || err.response.status == 422){
                    dispatch(errorsOnCoffeeReqest(err.response.data.errors));
                } else if(err.response.status == 401){
                    dispatch(signoutUser());
                }
                // TODO handle other exceptions
            });
    };
};
// when coffee is successfully created
export const coffeeCreated = (coffee) => {
    return {
        type:  CREATED_COFFEE,
        coffee: coffee,
    };
}

export const errorsOnCoffeeReqest = (errors) => {
    return {
        type:  ERROR_COFFEE_REQEST,
        errors: errors,
    };
}

export const coffeeDeleted = (coffee) => {
    return {
        type:  DELETED_COFFEE,
        coffee: coffee,
    };
};