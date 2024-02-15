import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import store from '../store'
import { BrowserRouter, Route, Switch } from 'react-router-dom';
import { hot } from 'react-hot-loader';
import RequireAuth from '../components/auth/require_auth';
import Signin from '../components/auth/signin';
import Signout from '../components/auth/signout';
import Signup from '../components/auth/signup';
import CoffeeShop from '../components/CoffeeShop';

class App extends React.Component {


  render() {
    return (
      <Provider store={store}>
       <div className="app">
        <BrowserRouter>
          <Route exact path="/" component={RequireAuth(CoffeeShop)} />
          <Route exact path="/signin" component={Signin} />
          <Route exact path="/signout" component={Signout} />
          <Route exact path="/signup" component={Signup} />
        </BrowserRouter>
      </div>
    </Provider>
    );
  }
}

export default hot(module)(App);
