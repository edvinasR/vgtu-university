import React, { PureComponent } from 'react';
import { Field, reduxForm } from 'redux-form';
import * as actions from '../../actions';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import { Redirect } from 'react-router';

class Signup extends PureComponent {

    handleFormSubmit(formProps) {
        this.props.signupUser(formProps)
    }

    renderField = ({ input, label, type, meta: { touched, error } }) => (
        <div>
            <label>{label}</label>
            <div>
                <input className="form-control" {...input} placeholder={label} type={type} />
                {touched && error && <span className="text-danger">{error}</span>}
            </div>
        </div>
    );


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
        const { handleSubmit, submitting } = this.props;
        if(this.props.authenticated ) {
            return <Redirect to='/'/>;
        };

        return (
            
            <div className="authContainer">
                <h2 className="center">Register</h2>
                <form onSubmit={handleSubmit(this.handleFormSubmit.bind(this))}>
                    <fieldset className="form-group">
                        <Field
                            name="email"
                            label="Email"
                            component={this.renderField}
                            type="text" />
                    </fieldset>
                    <fieldset className="form-group">
                        <Field
                            name="name"
                            label="Name"
                            component={this.renderField}
                            type="text" />
                    </fieldset>
                    <fieldset className="form-group">
                        <Field
                            name="password"
                            label="Password"
                            component={this.renderField}
                            type="password" />
                    </fieldset>
                    <fieldset className="form-group">
                        <Field
                            name="confirm_password"
                            label="Password Confirmation"
                            component={this.renderField}
                            type="password" />
                    </fieldset>
                    {this.renderError()}
                    <button type="submit" className="btn btn-primary" disabled={submitting}>Sign Up</button>
                    <a className="right" href={'/signin'} >Login</a>
                </form>
            </div>
        );
    }
}

const validate = values => {
    let errors = {};

    if (!values.email) {
        errors.email = 'Please enter an email';
    } else if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(values.email)) {
        errors.email = 'Invalid email address';
    }else if (values.email.length > 255) {
        errors.email = 'Email is too long';
    }

    if (!values.name) {
        errors.name = 'Please enter name';
    } else if (values.name.length > 255) {
        errors.name = 'Name is too long';
    }

    if (!values.password) {
        errors.password = 'Please enter an password';
    }else if (values.password.length < 7) {
        errors.password = 'Password must be at least 7 characters';
    }


    if (!values.confirm_password) {
        errors.confirm_password = 'Please enter an password confirmation';
    }

    if (values.password !== values.confirm_password) {
        errors.password = 'Password must match';
    }

    return errors;
};

const mapStateToProps = (state) => {
    return { 
        authErrors: state.auth.errors,
        authenticated: state.auth.authenticated,
    }
};

export default reduxForm({
    form: 'signin',
    validate
})(connect(mapStateToProps, actions)(Signup));
