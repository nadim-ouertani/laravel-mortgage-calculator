import React from 'react';
import './FormField.css';

interface FormFieldProps {
  label: string;
  name: string;
  type: string;
  value: number | string;
  onChange: (name: string, value: number) => void;
  onBlur: (name: string, value: number) => void;
  error?: string;
  min?: number;
  max?: number;
  step?: number;
  required?: boolean;
  placeholder?: string;
  currency?: boolean;
}

const FormField: React.FC<FormFieldProps> = ({
  label,
  name,
  type,
  value,
  onChange,
  onBlur,
  error,
  min,
  max,
  step,
  required,
  placeholder,
  currency
}) => {
  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const numValue = parseFloat(e.target.value) || 0;
    onChange(name, numValue);
  };

  const handleBlur = (e: React.FocusEvent<HTMLInputElement>) => {
    const numValue = parseFloat(e.target.value) || 0;
    onBlur(name, numValue);
  };

  return (
    <div className="form-group">
      <label htmlFor={name}>
        {label} {currency && <span className="aed-currency"></span>}
      </label>
      <input
        type={type}
        id={name}
        name={name}
        value={value}
        onChange={handleChange}
        onBlur={handleBlur}
        min={min}
        max={max}
        step={step}
        required={required}
        placeholder={placeholder}
        className={error ? 'error' : ''}
      />
      {error && <div className="field-error">{error}</div>}
    </div>
  );
};

export default FormField;