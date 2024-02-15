import { createStore, applyMiddleware, compose } from 'redux';
import rootReducer from "../reducers";
import reduxThunk from 'redux-thunk';

const createStoreWithMiddleware = applyMiddleware(reduxThunk)(createStore);
const store = createStoreWithMiddleware(rootReducer);

export default store;




