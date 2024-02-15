import React, { PureComponent } from 'react';
import { Field, reduxForm } from 'redux-form';
import * as actions from '../../actions';
import { connect } from 'react-redux';
import { Redirect } from 'react-router';

class Signin extends PureComponent {

    handleFormSubmit({ email, password }) {
        this.props.signinUser({ email, password })
    }

    renderError() {
        if (this.props.authErrors) {
            let errorsData = this.props.authErrors;
            return (
                <div className="alert alert-danger">
                    {Object.keys(errorsData).map(key => (
                        <p key={key}> {errorsData[key]}</p>
                    ))}
                </div>
            );
        }
    }

    render() {
        const { handleSubmit } = this.props;
        if(this.props.authenticated ) {
            return <Redirect to='/'/>;
        };
        return (
            <div className="authContainer">
                <h2 className="center">Login</h2>
                <form onSubmit={handleSubmit(this.handleFormSubmit.bind(this))}>
                    <fieldset className="form-group">
                        <label>Email:</label>
                        <Field className="form-control" name="email" component="input" type="text" />
                    </fieldset>
                    <fieldset className="form-group">
                        <label>Password:</label>
                        <Field className="form-control" name="password" component="input" type="password" />
                    </fieldset>
                    {this.renderError()}
                    <button action="submit" className="btn btn-primary">Sign in</button>
                    <a className="right" href={'/signup'} >Register</a>
                </form>
    
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return { 
        authErrors: state.auth.errors,
        authenticated: state.auth.authenticated,
    }
};

export default reduxForm({
    form: 'signin'
})(connect(mapStateToProps, actions)(Signin));
