import React, { useState } from 'react';
import { LoanData } from '../types/loan';
import './LoanForm.css';

interface LoanFormProps {
  onSubmit: (data: LoanData) => void;
}

const LoanForm: React.FC<LoanFormProps> = ({ onSubmit }) => {
  const [formData, setFormData] = useState<LoanData>({
    loan_amount: 250000, // Realistic starting amount
    annual_interest_rate: 4.5,
    loan_term_years: 25,
    monthly_extra_payment: 0
  });

  const [errors, setErrors] = useState<{ [key: string]: string }>({});
  const [hasExtraPayment, setHasExtraPayment] = useState(false);

  // Input validation
  const validateField = (name: string, value: number, currentFormData?: LoanData): string => {
    const currentData = currentFormData || formData;
    
    switch (name) {
      case 'loan_amount':
        if (value < 5000) return 'Loan amount must be at least AED 5,000';
        if (value > 4000000) return 'Loan amount cannot exceed AED 4,000,000';
        return '';
      
      case 'annual_interest_rate':
        if (value < 0) return 'Interest rate cannot be negative';
        return '';
      
      case 'loan_term_years':
        if (value < 1) return 'Loan term must be at least 1 year';
        return '';
      
      case 'monthly_extra_payment':
        if (value < 0) return 'Extra payment cannot be negative';
        // extra payment can't be >= loan amount
        if (value >= currentData.loan_amount) return 'Extra payment cannot be equal or greater than total loan amount';
        return '';
      
      default:
        return '';
    }
  };

  const handleSliderChange = (name: string, value: number) => {
    const newFormData = { ...formData, [name]: value };
    setFormData(newFormData);
    
    // Re-validate extra payment when loan amount changes
    if (name === 'loan_amount' && (formData.monthly_extra_payment || 0) > 0) {
      const extraError = validateField('monthly_extra_payment', formData.monthly_extra_payment || 0, newFormData);
      if (extraError) {
        setErrors(prev => ({ ...prev, monthly_extra_payment: extraError }));
      } else {
        setErrors(prev => ({ ...prev, monthly_extra_payment: '' }));
      }
    }
    
    // Clear error for current field when user interacts
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: '' }));
    }
  };

  const validateForm = (): boolean => {
    const newErrors: { [key: string]: string } = {};
    
    Object.entries(formData).forEach(([key, value]) => {
      if (key === 'monthly_extra_payment' && !value) return; // Optional field
      const error = validateField(key, value);
      if (error) newErrors[key] = error;
    });

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (validateForm()) {
      onSubmit(formData);
    }
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-AE', {
      style: 'currency',
      currency: 'AED',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(amount);
  };

  return (
    <div className="loan-form-container">
      <div className="form-header">
        <h2>Mortgage Loan Calculator</h2>
        <p>Calculate your monthly payments with our mortgage calculator</p>
      </div>

      <form className="modern-loan-form" onSubmit={handleSubmit}>
        {/* Loan Amount Slider */}
        <div className="form-field">
          <label className="field-label">
            Loan Amount: <span className="amount-display">{formatCurrency(formData.loan_amount)}</span>
          </label>
          <input
            type="range"
            min={5000}
            max={4000000}
            step={5000}
            value={formData.loan_amount}
            onChange={(e) => handleSliderChange('loan_amount', Number(e.target.value))}
            className="loan-slider"
          />
          <div className="slider-labels">
            <span>AED 5K</span>
            <span>AED 4M</span>
          </div>
          {errors.loan_amount && <span className="error-message">{errors.loan_amount}</span>}
        </div>

        {/* Interest Rate Input */}
        <div className="form-field">
          <label className="field-label">
            Annual Interest Rate: <span className="rate-display">{formData.annual_interest_rate}%</span>
          </label>
          <input
            type="number"
            min={0.1}
            step={0.1}
            value={formData.annual_interest_rate}
            onChange={(e) => handleSliderChange('annual_interest_rate', Number(e.target.value))}
            className="term-input"
            placeholder="Enter annual interest rate (%)"
          />
          {errors.annual_interest_rate && <span className="error-message">{errors.annual_interest_rate}</span>}
        </div>

        {/* Loan Term Input */}
        <div className="form-field">
          <label className="field-label">Loan Term (Years)</label>
          <input
            type="number"
            min={1}
            value={formData.loan_term_years}
            onChange={(e) => handleSliderChange('loan_term_years', Number(e.target.value))}
            className="term-input"
            placeholder="Enter loan term in years"
          />
          {errors.loan_term_years && <span className="error-message">{errors.loan_term_years}</span>}
        </div>

        {/* Extra Payment Option */}
        <div className="form-field">
          <div style={{ display: 'flex', alignItems: 'center', marginBottom: '1rem' }}>
            <input
              type="checkbox"
              id="hasExtraPayment"
              checked={hasExtraPayment}
              onChange={(e) => {
                setHasExtraPayment(e.target.checked);
                if (!e.target.checked) {
                  setFormData(prev => ({ ...prev, monthly_extra_payment: 0 }));
                } else {
                  setFormData(prev => ({ ...prev, monthly_extra_payment: 1 }));
                }
              }}
              style={{ marginRight: '0.5rem' }}
            />
            <label htmlFor="hasExtraPayment" className="field-label" style={{ margin: 0 }}>
              Add Monthly Extra Payment
            </label>
          </div>
          
          {hasExtraPayment && (
            <>
              <label className="field-label">
                Monthly Extra Payment: <span className="amount-display">{formatCurrency(formData.monthly_extra_payment || 0)}</span>
              </label>
              <input
                type="range"
                min={1}
                max={formData.loan_amount - 1}
                step={1}
                value={formData.monthly_extra_payment || 1}
                onChange={(e) => handleSliderChange('monthly_extra_payment', Number(e.target.value))}
                className="extra-slider"
              />
              <div className="slider-labels">
                <span>AED 1</span>
                <span>{formatCurrency(formData.loan_amount - 1)}</span>
              </div>
            </>
          )}
          
          <small className="field-hint">Tip: Extra payments reduce total interest and loan term</small>
          {errors.monthly_extra_payment && <span className="error-message">{errors.monthly_extra_payment}</span>}
        </div>

        <button type="submit" className="calculate-button">
          Calculate My Loan
        </button>
      </form>
    </div>
  );
};

export default LoanForm;