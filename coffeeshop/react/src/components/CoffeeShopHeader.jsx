import React from 'react';
import ReactDOM from 'react-dom';
import CreateCoffeeModal from '../components/CreateCoffeeModal';

class CoffeeShopHeader extends React.Component {

  render() {
    return (
        <div>
          <div className="links right">
            <CreateCoffeeModal/>
            <a  className="btn btn-link" href={'/signout'} >Logout</a>
          </div>
          <div className="shopHeader">
          </div>
          <hr/>
        </div>
      );
  }
}

export default CoffeeShopHeader;
