import { combineReducers } from 'redux';
import { reducer as authReducer } from './auth';
import { reducer as coffeeReducer } from './coffee';
import { reducer as formReducer } from 'redux-form';

const rootReducer = combineReducers({
    form: formReducer,
    auth: authReducer,
    coffeeReducer: coffeeReducer
});

export default rootReducer;
