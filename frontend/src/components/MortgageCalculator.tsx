import React, { useState } from 'react';
import LoanForm from './LoanForm';
import LoanResults from './LoanResults';
import LoadingSpinner from './LoadingSpinner';
import ErrorMessage from './ErrorMessage';
import { LoanData, LoanCalculationResponse } from '../types/loan';
import { calculateLoan } from '../services/api';
import './MortgageCalculator.css';

const MortgageCalculator: React.FC = () => {
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [results, setResults] = useState<LoanCalculationResponse | null>(null);

  const handleCalculate = async (loanData: LoanData) => {
    setIsLoading(true);
    setError(null);
    setResults(null);

    try {
      const response = await calculateLoan(loanData);
      setResults(response);
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message);
      } else {
        setError('An unexpected error occurred. Please try again.');
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="mortgage-calculator">
      <div className="container">
        <div className="calculator-section">
          <LoanForm onSubmit={handleCalculate} />
          
          {isLoading && <LoadingSpinner />}
          
          {error && <ErrorMessage message={error} />}
          
          {results && <LoanResults data={results} />}
        </div>
      </div>
    </div>
  );
};

export default MortgageCalculator;