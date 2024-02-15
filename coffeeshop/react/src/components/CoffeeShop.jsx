import React from 'react';
import 'assets/scss/App.scss';
import CoffeeShopHeader from '../components/CoffeeShopHeader';
import CoffeeShopBillboard from '../components/CoffeeShopBillboard';

// number of coffees in single row
const NUMBER_OF_COLUMNS = 4;
class CoffeeShop extends React.Component {

  render() {
    return (
      <div>
          <CoffeeShopHeader/>
          <CoffeeShopBillboard numberOfCols={NUMBER_OF_COLUMNS}/>
      </div>
    );
  }
}

 export default CoffeeShop