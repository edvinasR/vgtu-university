
import React from 'react';
import { render } from 'react-dom';
import App from 'components/App';
import store from './store'
import { AUTH_USER } from './actions/types';
import { fetchAllCoffees } from './actions';

  const rootEl = document.getElementById('root');
  const token = localStorage.getItem('token');
  if (token) {
    store.dispatch({ type: AUTH_USER });
    store.dispatch(fetchAllCoffees());
  }
render(<App/>, rootEl);