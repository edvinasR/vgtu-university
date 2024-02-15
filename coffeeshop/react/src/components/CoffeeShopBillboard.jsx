import React from 'react';
import 'assets/scss/App.scss';
import CoffeeItem from '../components/CoffeeItem';
import { connect } from 'react-redux';
import { fetchAllCoffees } from '../actions';

class CoffeeShopBillboard extends React.Component {

  componentDidMount(){
    this.props.dispatch(fetchAllCoffees()); 
  }

  //Method renders row and N items inside it strating indexFrom and to indexTo of state data
  renderRow(indexFrom, indexTo){
    let coffees = [];

    for(let i = indexFrom; i < indexTo; i++ ) {
      let coffee = this.props.coffees[i];
      if(coffee) {
        // Pushing coffee item if exists
        coffees.push(<CoffeeItem  key={i} coffee={coffee}/>);
      } else {
        // If no coffee at given index pushing empty col div
        coffees.push(<div key={i} className="col"></div>);
      }
    }
    return(
      <div key={"row_"+indexFrom} className="row">
        {coffees}
      </div>
    )
  }
  //Method renders new row every N coffee items
  renderCoffees() {
    let coffeeRows = []

    if(this.props.coffees){
      this.props.coffees.forEach((coffee, index) =>{
        if((index) % this.props.numberOfCols == 0){
          coffeeRows.push(
            this.renderRow(index,(index + this.props.numberOfCols))
          );
        }
      });
    }

    return (
      <div className="container coffeeShop">
          {coffeeRows}
      </div>
    );
  }

  render() {
    return (
      <div>
          {this.renderCoffees()}
      </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    coffees : state.coffeeReducer.coffees
  }
}


export default connect(mapStateToProps)(CoffeeShopBillboard);
