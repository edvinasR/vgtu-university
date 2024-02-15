import React from 'react';
import 'assets/scss/App.scss';
import { connect } from 'react-redux';
import { deleteCoffee } from '../actions';


class CoffeeItem extends React.Component {

  constructor(props) {
    super(props);
    this.dispatchCoffeeDeletion = this.dispatchCoffeeDeletion.bind(this);
  }  

  dispatchCoffeeDeletion(){
    this.props.dispatch(deleteCoffee(this.props.coffee));
  }

  render() {
    return (
      <div className="card col">
        <img className="coffeItemImage" src={this.props.coffee.image} alt={this.props.coffee.title}/>
        <span className="close" onClick={this.dispatchCoffeeDeletion}>x</span>
        <div className="cardFooter">
          <div>
            <b>
              {this.props.coffee.title}
            </b>
          </div> 
          <div>{this.props.coffee.price}</div>   
        </div>
      </div>
    );
  }
  
}

function mapStateToProps(state) {
  return {
  };
}

export default connect(mapStateToProps)(CoffeeItem);
