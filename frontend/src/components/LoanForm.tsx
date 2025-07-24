import React, { useState } from 'react';
import { LoanData } from '../types/loan';

interface LoanFormProps {
  onSubmit: (data: LoanData) => void;
}

const LoanForm: React.FC<LoanFormProps> = ({ onSubmit }) => {
  const [formData, setFormData] = useState<LoanData>({
    loan_amount: 250000,
    annual_interest_rate: 4.5,
    loan_term_years: 25,
    monthly_extra_payment: 0
  });

  const [errors, setErrors] = useState<{ [key: string]: string }>({});

  const validateField = (name: string, value: number): string => {
    switch (name) {
      case 'loan_amount':
        if (value < 5000) return 'Loan amount must be at least AED 5,000';
        return '';
      case 'annual_interest_rate':
        if (value < 0) return 'Interest rate cannot be negative';
        return '';
      case 'loan_term_years':
        if (value < 1) return 'Loan term must be at least 1 year';
        return '';
      case 'monthly_extra_payment':
        if (value < 0) return 'Extra payment cannot be negative';
        return '';
      default:
        return '';
    }
  };

  const handleInputChange = (name: string, value: number) => {
    setFormData(prev => ({ ...prev, [name]: value }));
    
    const error = validateField(name, value);
    setErrors(prev => ({ ...prev, [name]: error }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    const newErrors: { [key: string]: string } = {};
    Object.entries(formData).forEach(([key, value]) => {
      const error = validateField(key, value);
      if (error) newErrors[key] = error;
    });

    if (Object.keys(newErrors).length === 0) {
      onSubmit(formData);
    } else {
      setErrors(newErrors);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>Mortgage Loan Calculator</h2>
      
      <div>
        <label>Loan Amount (AED):</label>
        <input
          type="number"
          value={formData.loan_amount}
          onChange={(e) => handleInputChange('loan_amount', Number(e.target.value))}
        />
        {errors.loan_amount && <span style={{color: 'red'}}>{errors.loan_amount}</span>}
      </div>

      <div>
        <label>Annual Interest Rate (%):</label>
        <input
          type="number"
          step="0.1"
          value={formData.annual_interest_rate}
          onChange={(e) => handleInputChange('annual_interest_rate', Number(e.target.value))}
        />
        {errors.annual_interest_rate && <span style={{color: 'red'}}>{errors.annual_interest_rate}</span>}
      </div>

      <div>
        <label>Loan Term (Years):</label>
        <input
          type="number"
          value={formData.loan_term_years}
          onChange={(e) => handleInputChange('loan_term_years', Number(e.target.value))}
        />
        {errors.loan_term_years && <span style={{color: 'red'}}>{errors.loan_term_years}</span>}
      </div>

      <div>
        <label>Monthly Extra Payment (Optional):</label>
        <input
          type="number"
          value={formData.monthly_extra_payment || 0}
          onChange={(e) => handleInputChange('monthly_extra_payment', Number(e.target.value))}
        />
        {errors.monthly_extra_payment && <span style={{color: 'red'}}>{errors.monthly_extra_payment}</span>}
      </div>

      <button type="submit">Calculate Loan</button>
    </form>
  );
};

export default LoanForm;