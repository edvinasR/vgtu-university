import React from 'react';
import ReactDOM from 'react-dom';
import Popup from "reactjs-popup";
import { Field, reduxForm } from 'redux-form';
import * as actions from '../actions';
import { connect } from 'react-redux';

class CreateCoffeeModal extends React.Component {

    handleFormSubmit = (data) => {
        this.props.createCoffee(data);
    }

    renderError() {
        if (this.props.errors) {
            let errorsData = this.props.errors;
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

        return (
            <Popup trigger={ <button type="button" className="btn btn-link" >Add new</button>} modal>
                {close => (
                <div className="modalContainer">
                    <a className="close" onClick={close}>
                         &times;
                    </a>
                    <h2 className="center">Create coffee</h2>
                    <hr/>
                    <form  onSubmit={handleSubmit(this.handleFormSubmit.bind(this),close)}>
                        <fieldset className="form-group">
                            <Field
                                name="title"
                                label="Title"
                                component={renderField}
                                type="text" />
                        </fieldset>
                        <fieldset className="form-group">
                            <Field
                                name="price"
                                label="Price in EUR"
                                component={priceFiled}
                                type="number"
                             />
                        </fieldset>
                        
                        <fieldset className="form-group">
                            <Field
                                name="image"
                                label="Image"
                                component= {FileInput}
                                type="file"
                              />
                        </fieldset>
                        {this.renderError()}     
                        <button type="submit"  className="btn btn-primary" disabled={submitting}>Create</button>  
                    </form>
                </div>
                )}
            </Popup>
        );
    }
}

const adaptFileEventToValue = delegate => e => delegate(e.target.files[0]);

const FileInput = ({ input: { value: omitValue, onChange, onBlur, ...inputProps }, label, meta:  { touched, error }, ...props }) => {
  return (
    <div>
        <label>{label}</label>  
        <div>
            <input
            onChange={adaptFileEventToValue(onChange)}
            onBlur={adaptFileEventToValue(onBlur)}
            type="file" 
            accept="image/*"
            {...props.input}
            {...props}
            />
            <br/>
            {touched && error && <span className="text-danger">{error}</span>}
        </div>
    </div>    
  );
};

 const renderField = ({ input, label, type, meta: { touched, error } }) => (
        <div>
            <label>{label}</label>
            <div>
                <input className="form-control" {...input} placeholder={label} type={type} />
                {touched && error && <span className="text-danger">{error}</span>}
            </div>
        </div>
    );
    // price input
  const  priceFiled = ({ input, label, type, meta: { touched, error } }) => (
   
        <div>
            <label>{label}</label>
            <div>
                <input className="form-control" {...input} placeholder={label} type={type} min="0" step="0.01"/>
                {touched && error && <span className="text-danger">{error}</span>}
            </div>
        </div>
    );

const validate = values => {
    let errors = {};

    if (!values.title) {
        errors.title = 'Please enter title';
    } else if (values.title.length > 255) {
        errors.title = 'Title is too long';
    }

    if (!values.price) {
        errors.price = 'Please enter price';
    } else if (/^(\d*([.,](?=\d{3}))?\d+)+((?!\2)[.,]\d\d)?$/.test(values.price)) {
        errors.price = 'Price is invalid! Price must be in format $$.$$';
    }

    if(!values.image){
        errors.image = 'Please choose an image';
    }
    return errors;
};
const mapStateToProps = (state) => {
    return { 
        errors: state.coffeeReducer.errors,
    }
};

export default reduxForm({
    form: 'create_coffee',
    validate
})(connect(mapStateToProps, actions)(CreateCoffeeModal));
