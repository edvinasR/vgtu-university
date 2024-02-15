import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import * as actions from '../../actions';
import { Redirect } from 'react-router';

class Signout extends PureComponent {

    componentDidMount() {
        this.props.signoutUser();
    }

    render() {
        return <Redirect to='/signin'/>;
    }
}

export default connect(null, actions)(Signout);
